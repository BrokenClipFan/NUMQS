<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\DriverStatus;
use App\Models\Route;
use App\Services\TerminalService;
use App\Services\DriverAssignmentService;
use App\Services\QueueService;

#[Signature('jeepney:move')]
#[Description('Simulate real-time jeepney movement')]
class MoveJepneys extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(TerminalService $terminalService, DriverAssignmentService $assignmentService, QueueService $queueService)
    {
        $routeRecord = Route::where('route', 'Naga-To-Uling')->first();
        $nagaWifi = $terminalService->getTerminalByBssid("00:1A:2B:3C:4D:52");
        $ulingWifi = $terminalService->getTerminalByBssid("00:1A:2B:3C:4D:51");


        if (!$routeRecord) {
            $this->error('Route not found!');
            return;
        }

        $path = json_decode($routeRecord->path, true);
        $totalPoints = count($path);
        $this->info("Loaded path with {$totalPoints} points.");

        while (true) {
            // 1. Get all online drivers
            $drivers = DriverStatus::where('is_online', 1)->get();

            foreach ($drivers as $driver) {
                // 2. Add some "jitter" or simulation speed control
                if (mt_rand(1, 100) > 60) continue; 

                $index = $driver->waypoint_index;
                
                $index += ($driver->dispatched_to === "Uling") ? 1 : -1;

                $maxIndex = count($path) - 1;
                
                if ($index >= $maxIndex) {

                    if($nagaWifi->name == 'Uling Terminal' && $driver->state != "queued") {
                        $queueService->addToQueue($driver, $nagaWifi);
                        $driver->state = "queued";
                    }

                    $topDriver = $queueService->getTopPosition($nagaWifi);

                    if($topDriver->driver_profile_id == $driver->user_id 
                       && $topDriver->filling_at == null) {
                        $queueService->setFillingUp($driver);
                    }

                    if($queueService->getFillingAtMinutes($driver, $nagaWifi) >= 1) {
                        $index = $maxIndex;
                        $driver->dispatched_to = "Naga";
                        $driver->state = "in_route";
                        $queueService->removeFromQueue($driver);
                    } else {
                        $index--;
                    }

                } elseif ($index <= 0) {

                    if($ulingWifi->name == 'Naga Terminal' && $driver->state != "queued") {
                        $queueService->addToQueue($driver, $ulingWifi);
                        $driver->state = "queued";
                    }

                    $topDriver = $queueService->getTopPosition($ulingWifi);

                    if($topDriver->driver_profile_id == $driver->user_id 
                       && $topDriver->filling_at == null) {
                        $queueService->setFillingUp($driver);
                    }

                    if($queueService->getFillingAtMinutes($driver, $ulingWifi) >= 1) {
                        $index = 0;
                        $driver->dispatched_to = "Uling";
                        $driver->state = "in_route";
                        $queueService->removeFromQueue($driver);
                    } else {
                        $index++;
                    }
                }

                // 5. Update the driver (Single Database Call)

                $point = $path[$index];
                $driver->update([
                    'waypoint_index' => $index,
                    'latitude'       => $point['lat'],
                    'longitude'      => $point['lng'],
                    'dispatched_to'  => $driver->dispatched_to, // Ensure this updates if changed
                    'last_updated'   => now(),
                ]);
            }
            
            // 6. Sleep for a short duration to prevent CPU pinning
            usleep(1000000);
        }
    }
}

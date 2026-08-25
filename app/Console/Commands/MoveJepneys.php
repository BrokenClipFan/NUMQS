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
use App\Services\ViolationService;

#[Signature('jeepney:move')]
#[Description('Simulate real-time jeepney movement')]
class MoveJepneys extends Command
{
    /**
     * Execute the console command.
     */

    public function handle(TerminalService $terminalService, DriverAssignmentService $assignmentService, QueueService $queueService, ViolationService $violationService)
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
                // 2. Simulation speed control / jitter
                if (mt_rand(1, 100) > 90) continue; 

                $index = $driver->waypoint_index;
                $maxIndex = count($path) - 1;
                
                // 3. Move index based on physical intent (going_to)
                $index += ($driver->going_to === "Uling") ? 1 : -1;

                // 4. Safety Clamp: Prevents array key errors ($path[-1] or $path[max + 1])
                if ($index > $maxIndex) $index = $maxIndex;
                if ($index < 0) $index = 0;

                $driverIn = null;
                $driver->wifi_bssid = null;
                $currentTerminal = null;

                // 5. Detect if driver is physically at a terminal base
                if ($index === $maxIndex) {
                    $driverIn = "Uling";
                    $driver->wifi_bssid = "00:1A:2B:3C:4D:51";
                    $currentTerminal = $ulingWifi;
                } else if ($index === 0) {
                    $driverIn = "Naga";
                    $driver->wifi_bssid = "00:1A:2B:3C:4D:52";
                    $currentTerminal = $nagaWifi;
                }

                // 6. Handle terminal events if the driver is physically at one
                if ($driverIn !== null) {

                    if ($driverIn === $driver->dispatched_to) {
                        // --- HONEST DRIVER ---
                        if ($driver->state != "queued") {
                            $queueService->addToQueue($driver, $currentTerminal);
                            $driver->state = "queued";
                        }
                    } else {
                        // --- CHEATER DRIVER ---
                        // Arrived at a terminal, but it does not match their assigned dispatch
                        if ($driver->state != "queued") {
                            $this->warn("Driver {$driver->user_id} is cheating! Arrived at {$driverIn} but dispatched to {$driver->dispatched_to}.");
                            
                            // dd($currentTerminal);
                            $queueService->addToQueue($driver, $currentTerminal);
                            $driver->state = "queued";
                            
                            $violationService->createViolation( 
                                $driver->user_id,
                                $violationService::TYPE_UNAUTHORIZED_TERMINAL,
                                'Unauthorized Terminal Entry',
                                $driverIn, // e.g., "Naga" or "Uling"
                                [
                                    'expected_destination' => $driver->dispatched_to,
                                    'actual_destination'   => $driverIn,
                                    'waypoint_index'       => $index
                                ],
                                'high' // Severity level
                            );
                        }
                    }

                    // --- TERMINAL QUEUE ENGINE ---
                    // Runs uniformly for whoever is sitting in the queue
                    $topDriver = $queueService->getTopPosition($currentTerminal);

                    if ($topDriver && $topDriver->driver_profile_id == $driver->user_id && $topDriver->filling_at == null) {
                        $queueService->setFillingUp($driver);
                    }

                    $duration = $queueService->getFillingAtMinutes($driver, $currentTerminal);

                    if ($duration >= 1) {
                        // Dispatch them to the opposite end
                        $nextTerminal = $currentTerminal->name == "Uling" ? "Naga" : "Uling";

                        $driver->dispatched_to = $nextTerminal;
                        $driver->going_to = $nextTerminal; 
                        $driver->state = "in_route";
                        
                        // Force index 1 step out of terminal boundaries to break queue loop lock
                        $index += ($nextTerminal === "Uling") ? 1 : -1; 
                        
                        $queueService->removeFromQueue($driver);
                    } 
                }
                
                // 7. Extract map data and persist state updates
                $point = $path[$index];
                $driver->update([
                    'waypoint_index' => $index,
                    'latitude'       => $point['lat'],
                    'longitude'      => $point['lng'],
                    'dispatched_to'  => $driver->dispatched_to,
                    'going_to'       => $driver->going_to,
                    'state'          => $driver->state,
                    'last_updated'   => now(),
                ]);
            }

            sleep(2);
        }
    }
}

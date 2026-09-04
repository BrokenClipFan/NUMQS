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
                                'wrong_terminal',
                                'Went to wrong terminal (Cheating)',
                                $driverIn, // e.g., "Naga" or "Uling"
                                [
                                    'Supposed to go to' => $driver->dispatched_to,
                                    'Caught cheating at' => $driverIn
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

                    // SIMULATION DISPATCH LOGIC:
                    // In real life, DriverController@updateLocation handles BSSID departure.
                    // For this simulation command, we must simulate them physically leaving.
                    if ($driver->state == 'queued') {
                        $duration = $queueService->getFillingAtMinutes($driver, $currentTerminal);
                        $shouldDispatch = false;

                        if ($currentTerminal->name == "Uling") {
                            // Uling dispatches strictly on a timer (e.g. 15 mins)
                            // We use 3 minutes here so the simulation doesn't take forever to watch
                            if ($duration >= 3) {
                                $shouldDispatch = true;
                            }
                        } else {
                            // Naga dispatches randomly when jeepney is full
                            // We simulate a random chance of getting full after at least 1 minute
                            if ($duration >= 1 && mt_rand(1, 100) > 70) {
                                $shouldDispatch = true;
                            }
                        }

                        if ($shouldDispatch) {
                            $nextTerminal = $currentTerminal->name == "Uling" ? "Naga" : "Uling";

                            $driver->dispatched_to = $nextTerminal;
                            $driver->going_to = $nextTerminal; 
                            $driver->state = "in_route";
                            
                            // Step out of terminal bounds so they start moving
                            $index += ($nextTerminal === "Uling") ? 1 : -1; 
                            
                            $queueService->removeFromQueue($driver);
                        }
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

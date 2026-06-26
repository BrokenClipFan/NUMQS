<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\DriverStatus;
use App\Models\Route;

#[Signature('jeepney:move')]
#[Description('Simulate real-time jeepney movement')]
class MoveJepneys extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
            $routeRecord = Route::where('route', 'Naga-To-Uling')->first();
        
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
                
                // 3. Update index based on destination
                // If Uling, move backwards (-1), otherwise forward (+1)
                $index += ($driver->dispatched_to === "Uling") ? -1 : 1;

                // 4. Boundary Logic (Clamp indices so they don't go out of range)
                $maxIndex = count($path) - 1;
                
                if ($index >= $maxIndex) {
                    $index = $maxIndex;
                    $driver->dispatched_to = "Uling";
                } elseif ($index <= 0) {
                    $index = 0;
                    $driver->dispatched_to = "Naga";
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
            usleep(200000); // 0.5 seconds
        }
    }
}

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
            $drivers = DriverStatus::where('is_online', 1)->get();

            foreach ($drivers as $driver) {
                // 2. Just access the array index (Extremely fast)
                if (mt_rand(1, 100) <= 80) {
                $index = $driver->waypoint_index;
                $point = $path[$index];

                $driver->latitude = $point['lat'];
                $driver->longitude = $point['lng'];
                $driver->save();

                // 3. Move to next point
                $driver->waypoint_index = ($driver->waypoint_index + 1) % $totalPoints;
                $driver->save();
                }
            }
            
            sleep(1);
        }
    }
}

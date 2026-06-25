<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\DriverStatus;

#[Signature('jeepney:move')]
#[Description('Simulate real-time jeepney movement')]
class MoveJepneys extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Jeepney simulation... Press Ctrl+C to stop.');
        
        while (true) {
            $drivers = DriverStatus::where('is_online', 1)->get();

            foreach ($drivers as $driver) {
                // Add a tiny bit of randomness to latitude and longitude
                // This simulates moving by a few meters every 2 seconds
                $driver->latitude += rand(-100, 100) / 100000;
                $driver->longitude += rand(-100, 100) / 100000;
                
                $driver->save();
            }
            sleep(2);
        }
    }
}

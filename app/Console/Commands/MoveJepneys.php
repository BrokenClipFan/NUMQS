<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\DriverStatus;

#[Signature('app:move-jepneys')]
#[Description('Command description')]
class MoveJepneys extends Command
{
    protected $signature = 'jeepney:move';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $jeepneys = DriverStatus::
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/Naga to Uling Route.json');

        $jsonContent = File::get($jsonPath);

        Route::updateOrCreate(
            ['route' => 'Naga-To-Uling'],
            ['path' => $jsonContent],
        );

        $this->command->info('Route imported Successfully');
    }
}

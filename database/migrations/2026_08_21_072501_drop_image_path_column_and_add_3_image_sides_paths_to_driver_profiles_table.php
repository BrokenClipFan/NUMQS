<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('driver_profiles', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->string('image_front_path')->default('vehicleFront/default.png')->after('plate_number');
            $table->string('image_side_path')->default('vehicleSide/default.png')->after('image_front_path');
            $table->string('image_back_path')->default('vehicleBack/default.png')->after('image_side_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_profiles', function (Blueprint $table) {
            
        });
    }
};

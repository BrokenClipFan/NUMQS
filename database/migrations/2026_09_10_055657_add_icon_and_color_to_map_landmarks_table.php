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
        Schema::table('map_landmarks', function (Blueprint $table) {
            $table->string('icon')->default('bi-geo-alt-fill')->after('type');
            $table->string('color')->default('#f97316')->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down(): void
    {
        Schema::table('map_landmarks', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color']);
        });
    }
};

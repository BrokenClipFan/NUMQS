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
        Schema::create('driver_queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_profile_id')->constrained();
            $table->foreignId('terminal_id')->constrained();
            $table->timestamp('queued_at');
            $table->timestamp('filling_at')->nullable();
            $table->integer('position');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_queues');
    }
};

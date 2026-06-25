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
        Schema::create('driver_statuses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();

            $table->decimal('latitude', 10, 7)->default(0.00);
            $table->decimal('longitude', 10, 7)->default(0.00);
            $table->string('dispatched_to')->nullable();
            $table->string('state')->default('idle');
            $table->datetime('last_updated')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_statuses');
    }
};

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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_profile_id');
            
            $table->string('type');          // e.g., 'unauthorized_terminal_entry', 'overspeeding'
            $table->string('name');          // Human readable title
            $table->text('location');        // String or serialized JSON coordinates
            $table->string('severity')->default('medium'); 
            
            $table->json('properties')->nullable(); 

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps(); 
            
            $table->foreign('driver_profile_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DriverQueue extends Model
{
    protected $fillable = [
        'driver_profile_id',
        'terminal_id',
        'queued_at',
        'filling_at',
        'position',
    ];

    public function profile() {
        return $this->belongsTo(DriverProfile::class, 'driver_profile_id', 'id');
    }

    public function status() {
        return $this->belongsTo(DriverStatus::class, 'driver_profile_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'address',
        'emergency_name',
        'emergency_phone',
        'birthdate',
        'license_number',
        'plate_number',
    ];

    protected $casts = [
        'birthdate' => 'datetime',
    ];
}
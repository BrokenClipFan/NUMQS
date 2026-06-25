<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverProfile extends Model
{

    use HasFactory;

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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
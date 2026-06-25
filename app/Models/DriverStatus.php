<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DriverStatus extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'dispatched_to',
        'state',
        'last_updated',
        'is_online'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

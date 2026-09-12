<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapLandmark extends Model
{
    protected $fillable = [
        'name',
        'type',
        'latitude',
        'longitude',
        'image_path',
        'icon',
        'color',
    ];
}

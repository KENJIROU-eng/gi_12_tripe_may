<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapItinerary extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date_id',
        'destination',
        'place_name',
        'latitude',
        'longitude',
        'distance_km',
        'duration_text',
        'place_id',
    ];
}

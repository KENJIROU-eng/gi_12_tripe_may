<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapItinerary extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date_id',
        'place_name',
        'destination',
        'distance_km',
        'duration_text'
    ];
}

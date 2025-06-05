<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateItinerary extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'itinerary_id', 'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function mapItineraries() {
        return $this->hasMany(MapItinerary::class, 'date_id');
    }
}

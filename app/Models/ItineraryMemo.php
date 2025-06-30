<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryMemo extends Model
{
    protected $fillable = ['itinerary_id', 'content'];

    public function itinerary() {
        return $this->belongsTo(Itinerary::class);
    }
}

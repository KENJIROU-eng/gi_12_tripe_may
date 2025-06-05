<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Belonging extends Model
{
    protected $fillable = ['itinerary_id', 'name', 'checked'];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}

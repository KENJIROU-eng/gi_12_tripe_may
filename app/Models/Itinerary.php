<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{

    use HasFactory;

    protected $fillable = [
        'created_by',
        'group_id',
        'title',
        'start_date',
        'end_date',
        'initial_place_name',
        'initial_latitude',
        'initial_longitude',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function dateItineraries() {
        return $this->hasMany(DateItinerary::class);
    }

    public function belongings() {
        return $this->hasMany(Belonging::class);
    }
}

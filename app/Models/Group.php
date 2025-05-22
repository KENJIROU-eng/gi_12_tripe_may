<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function itineraries() {
        return $this->hasMany(Itinerary::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'group_members');
    }


}

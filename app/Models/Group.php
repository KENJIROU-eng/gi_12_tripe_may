<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'user_id',
    ];

    public function itineraries() {
        return $this->hasMany(Itinerary::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'group_members');
    }


    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function members(){
        return $this->hasMany(GroupMember::class);
    }

}

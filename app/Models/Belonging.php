<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Belonging extends Model
{
    protected $fillable = ['itinerary_id', 'description', 'name', 'checked'];

    protected $casts = [
        'checked' => 'boolean',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_checked')->withTimestamps();
    }

    // 動的に全員チェック済みかどうかを返す
    public function getIsFullyCheckedAttribute()
    {
        return $this->users->every(fn($user) => $user->pivot->is_checked);
    }

}

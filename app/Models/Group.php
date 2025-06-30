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

    public static function getOrCreatePersonalGroup($userId)
    {
        // 自分が作成者であり、かつ自分だけが所属するグループを探す
        $group = Group::where('user_id', $userId)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->withCount('users')
            ->get()
            ->first(fn($g) => $g->users_count === 1);

        if ($group) {
            return $group;
        }

        // 存在しなければ新しく作成（初期名は 'Bocci'）
        $group = Group::create([
            'name' => 'Bocci',
            'user_id' => $userId,
        ]);

        $group->users()->attach($userId);

        return $group;
    }

    public function isBocciFor($userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $this->user_id == $userId
            && $this->users()->count() === 1
            && $this->users->pluck('id')->contains($userId);
    }
}

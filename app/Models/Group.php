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
        $groupName = 'Bocci';

        // 自分が所属している Bocci グループがあるか？
        $group = Group::where('name', $groupName)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->first();

        if ($group) {
            return $group;
        }

        // 自分専用の Bocci グループがまだないので作成
        $group = Group::create([
            'name' => $groupName,
            'user_id' => $userId, // ← グループ作成者を記録（必須）
        ]);

        // 自分自身をグループに追加
        $group->users()->attach($userId);

        return $group;
    }





}

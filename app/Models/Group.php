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
        // すでに「一人グループ」があるか確認（user_id を owner として使う想定）
        $group = self::where('user_id', $userId)
            ->where('name', 'like', "ボッチ専用")
            ->first();

        if (!$group) {
            // なければ作成
            $group = self::create([
                'name' => "ボッチ専用",
                'user_id' => $userId,
                'image' => null, // 必要であれば画像を指定
            ]);

            // 中間テーブルにもユーザーを追加
            $group->users()->attach($userId);
        }

        return $group;
    }


}

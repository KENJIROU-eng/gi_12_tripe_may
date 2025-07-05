<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'user_id', 'description', 'image', 'visibility','itinerary_id',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryPost()
    {
        return $this->HasMany(CategoryPost::class);
    }

    public function likes()
    {
        return $this->HasMany(Like::class);
    }

    public function comments()
    {
        return $this->HasMany(Comment::class);
    }

    public function visibleUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_visibility');
    }

    public function visibleGroups()
    {
        return $this->belongsToMany(Group::class, 'post_group_visibility');
    }

    public function isVisibleTo(User $viewer): bool
    {
        // 投稿者は常に閲覧可能
        if ($this->user_id === $viewer->id) {
            return true;
        }

        // 公開設定ごとの表示制御
        if ($this->visibility === 'public') {
            return true;
        }

        if ($this->visibility === 'self') {
            return false; // 投稿者以外には非公開
        }

        if ($this->visibility === 'followers') {
            return $this->user->followers->contains($viewer->id);
        }

        if ($this->visibility === 'groups') {
            return $this->visibleGroups
                ->intersect($viewer->groups)->isNotEmpty();
        }

        if ($this->visibility === 'custom') {
            return $this->visibleUsers->contains($viewer->id);
        }

        return false; // デフォルトは非表示
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function mapItineraries()
    {
        return $this->belongsToMany(MapItinerary::class, 'post_map_itinerary');
    }


}

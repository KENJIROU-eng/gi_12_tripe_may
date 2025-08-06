<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Post;
use App\Models\Group;

// Broadcast::channel('posts.{postId}', function ($user, $postId) {
    // return (int) $user->id === (int) $userId;
    // return Post::where('id', $postId)->where('user_id', $user->id)->exists();
    // return true;

Broadcast::channel('group.{groupId}', function ($user, $groupId) {

    // return true;
    if(!$user){
        return false;//認証済みではない場合はアクセス拒否
    }
    $group = Group::findOrFail($groupId);
    return $group->members()->where('user_id', $user->id)->exists();
});

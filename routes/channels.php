<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Post;

Broadcast::channel('posts.{postId}', function ($user, $postId) {
    // return (int) $user->id === (int) $userId;
    return Post::where('id', $postId)->where('user_id', $user->id)->exists();
    // return true;
});

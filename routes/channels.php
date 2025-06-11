<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Post;
use App\Models\Group;
Broadcast::channel('group.{groupId}', function ($user, $groupId) {

    return true;
    // if(!$user){
    //     return false;//認証済みではない場合はアクセス拒否
    // }
    // return $user->groups()->where('id', $groupId)->exists();
});

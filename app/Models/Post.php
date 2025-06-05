<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'user_id', 'description', 'image'];

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
}

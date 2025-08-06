<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelongingUser extends Model
{
    protected $table = 'belonging_user';

    protected $fillable = ['belonging_id', 'user_id', 'is_checked'];

    public function belonging()
    {
        return $this->belongsTo(Belonging::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

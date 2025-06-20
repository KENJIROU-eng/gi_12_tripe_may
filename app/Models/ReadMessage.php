<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadMessage extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'message_id',
    ];
}

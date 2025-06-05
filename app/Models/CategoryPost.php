<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
        'post_id',
        'category_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'city'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}

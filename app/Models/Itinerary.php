<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{

    use HasFactory;

    protected $fillable = [
        'created_by',
        'group_id',
        'title',
        'start_date',
        'end_date',
        'initial_place',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillUser extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_paid_id',
        'bill_id',
        'eachPay',
    ];

    public function userPaid()
    {
        return $this->belongsTo(User::class, 'user_paid_id');
    }
}

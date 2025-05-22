<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    public function userPay()
    {
        return $this->belongsTo(User::class, 'user_pay_id');
    }

    public function billUser()
    {
        return $this->HasMany(BillUser::class);
    }
}

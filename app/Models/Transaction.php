<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'datetime',
        'total',
        'pay_total',
    ];
    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }
}

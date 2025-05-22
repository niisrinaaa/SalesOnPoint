<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }

    public function item(){
        return $this-> belongsTo(item::class, 'items_id');
    }
}

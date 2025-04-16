<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'items_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}

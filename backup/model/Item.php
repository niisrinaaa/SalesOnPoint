<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'categories_id',
    ];

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'items_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}

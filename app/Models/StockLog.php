<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'old_stock',
        'new_stock',
        'change_amount',
        'change_type',
        'notes',
        'admin_user'
    ];

    // Relationship
    public function package()
    {
        return $this->belongsTo(RobuxPackage::class, 'package_id');
    }

    // Scope berdasarkan tipe perubahan
    public function scopeRestock($query)
    {
        return $query->where('change_type', 'restock');
    }

    public function scopePurchase($query)
    {
        return $query->where('change_type', 'purchase');
    }
}
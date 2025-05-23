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

    // Relationships
    public function package()
    {
        return $this->belongsTo(RobuxPackage::class, 'package_id');
    }

    // Helper methods
    public function getChangeTypeTextAttribute()
    {
        $types = [
            'restock' => 'Penambahan Stok',
            'sold' => 'Terjual',
            'adjustment' => 'Penyesuaian'
        ];

        return $types[$this->change_type] ?? $this->change_type;
    }

    public function getChangeTypeBadgeAttribute()
    {
        $badges = [
            'restock' => 'badge-success',
            'sold' => 'badge-info',
            'adjustment' => 'badge-warning'
        ];

        return $badges[$this->change_type] ?? 'badge-secondary';
    }

    public function getFormattedChangeAttribute()
    {
        $sign = $this->change_amount >= 0 ? '+' : '';
        return $sign . $this->change_amount;
    }
}
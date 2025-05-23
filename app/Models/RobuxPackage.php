<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobuxPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'price',
        'stock',
        'description',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(RobuxTransaction::class, 'package_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'package_id');
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getPricePerRobuxAttribute()
    {
        return $this->amount > 0 ? $this->price / $this->amount : 0;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusBadgeAttribute()
    {
        $badges = [
            'out_of_stock' => 'badge-danger',
            'low_stock' => 'badge-warning',
            'in_stock' => 'badge-success'
        ];

        return $badges[$this->stock_status] ?? 'badge-secondary';
    }

    // Update stock with logging
    public function updateStock($newStock, $notes = null, $adminUser = null)
    {
        $oldStock = $this->stock;
        $changeAmount = $newStock - $oldStock;
        
        // Determine change type
        $changeType = 'adjustment';
        if ($changeAmount > 0) {
            $changeType = 'restock';
        } elseif ($changeAmount < 0) {
            $changeType = 'sold';
        }

        // Update stock
        $this->update(['stock' => $newStock]);

        // Log the change
        StockLog::create([
            'package_id' => $this->id,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'change_amount' => $changeAmount,
            'change_type' => $changeType,
            'notes' => $notes,
            'admin_user' => $adminUser
        ]);

        return $this;
    }

    // Scope for active packages
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for packages with stock
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope for low stock packages
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<', $threshold)->where('stock', '>', 0);
    }
}
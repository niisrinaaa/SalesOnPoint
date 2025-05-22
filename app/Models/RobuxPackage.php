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
        'is_active',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationship dengan transactions
    public function transactions()
    {
        return $this->hasMany(RobuxTransaction::class, 'package_id');
    }

    // Relationship dengan stock logs
    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'package_id');
    }

    // Scope untuk package aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk package dengan stok
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Method untuk update stok
    public function updateStock($newStock, $notes = null, $adminUser = null)
    {
        $oldStock = $this->stock;
        $this->update(['stock' => $newStock]);

        // Log perubahan stok
        StockLog::create([
            'package_id' => $this->id,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'change_amount' => $newStock - $oldStock,
            'change_type' => 'adjustment',
            'notes' => $notes,
            'admin_user' => $adminUser
        ]);

        return $this;
    }

    // Method untuk kurangi stok saat pembelian
    public function decrementStock($amount = 1)
    {
        if ($this->stock >= $amount) {
            $oldStock = $this->stock;
            $this->decrement('stock', $amount);

            StockLog::create([
                'package_id' => $this->id,
                'old_stock' => $oldStock,
                'new_stock' => $this->stock,
                'change_amount' => -$amount,
                'change_type' => 'purchase'
            ]);

            return true;
        }
        return false;
    }

    // Format harga untuk display
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

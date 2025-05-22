<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RobuxTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'username_roblox',
        'package_id',
        'user_id',
        'robux_amount',
        'price_paid',
        'transaction_code',
        'payment_status',
        'delivery_status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'delivered_at'
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    // Generate transaction code otomatis
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = 'RBX' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function package()
    {
        return $this->belongsTo(RobuxPackage::class, 'package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeCompleted($query)
    {
        return $query->where('delivery_status', 'completed');
    }

    // Methods
    public function markAsPaid($paymentReference = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_reference' => $paymentReference,
            'paid_at' => now(),
            'delivery_status' => 'processing'
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'delivery_status' => 'completed',
            'delivered_at' => now()
        ]);
    }

    // Status badge untuk display
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-danger',
            'cancelled' => 'badge-secondary'
        ];

        return $badges[$this->payment_status] ?? 'badge-secondary';
    }

    public function getDeliveryStatusBadgeAttribute()
    {
        $badges = [
            'waiting' => 'badge-secondary',
            'processing' => 'badge-info',
            'completed' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return $badges[$this->delivery_status] ?? 'badge-secondary';
    }
}

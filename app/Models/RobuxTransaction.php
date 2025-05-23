<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RobuxTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'username_roblox',
        'robux_amount',
        'price_paid',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_proof',
        'delivery_status',
        'transaction_code',
        'delivered_at'
    ];

    protected $casts = [
        'price_paid' => 'decimal:0',
        'delivered_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(RobuxPackage::class, 'package_id');
    }

    // Helper methods
    public function markAsPaid($reference = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_reference' => $reference,
            'delivery_status' => 'processing'
        ]);
    }

    public function getDeliveryBadgeAttribute()
    {
        return match($this->delivery_status) {
            'waiting' => 'badge-secondary',
            'processing' => 'badge-primary',
            'completed' => 'badge-success',
            'failed' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof && Storage::disk('public')->exists($this->payment_proof)) {
            return Storage::disk('public')->url($this->payment_proof);
        }
        return null;
    }

    public function markAsDelivered()
    {
        $this->update([
            'delivery_status' => 'completed',
            'delivered_at' => now()
        ]);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_paid, 0, ',', '.');
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'pending_verification' => 'badge-info',
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
            'processing' => 'badge-warning',
            'completed' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return $badges[$this->delivery_status] ?? 'badge-secondary';
    }

    public function getRouteKeyName()
{
    return 'transaction_code';
}

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'transaction_code',
        'payment_method',
        'status',
        'amount',
        'paid_at',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'failure_reason',
        'note',
        'deleted_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Payment thuộc về booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope: payments đã thanh toán
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: payments pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: lọc theo gateway
     */
    public function scopeByGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    /**
     * Get payment status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn tiền',
            default => $this->status,
        };
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Tiền mặt',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
            'transfer' => 'Chuyển khoản',
            default => $this->payment_method,
        };
    }

    /**
     * Check if payment is successful
     */
    public function getIsSuccessfulAttribute()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is refunded
     */
    public function getIsRefundedAttribute()
    {
        return $this->status === 'refunded';
    }
}

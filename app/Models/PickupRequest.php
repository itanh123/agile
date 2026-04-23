<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupRequest extends Model
{
    protected $fillable = [
        'booking_id',
        'pickup_code',
        'status',
        'pickup_staff_id',
        'pickup_address',
        'pickup_phone',
        'pickup_note',
        'scheduled_pickup_at',
        'actual_pickup_at',
        'delivered_at',
        'staff_notes',
    ];

    protected $casts = [
        'scheduled_pickup_at' => 'datetime',
        'actual_pickup_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_PICKED_UP = 'picked_up';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Chờ phân công',
        self::STATUS_ASSIGNED => 'Đã phân công',
        self::STATUS_PICKED_UP => 'Đã nhận thú',
        self::STATUS_DELIVERED => 'Đã giao trả',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    public const STATUS_COLORS = [
        self::STATUS_PENDING => 'warning',
        self::STATUS_ASSIGNED => 'info',
        self::STATUS_PICKED_UP => 'primary',
        self::STATUS_DELIVERED => 'success',
        self::STATUS_CANCELLED => 'danger',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function pickupStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pickup_staff_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }
}
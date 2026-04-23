<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'pet_id',
        'staff_id',
        'promotion_id',
        'appointment_at',
        'service_mode',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'discount_amount',
        'total_amount',
        'note',
        'booking_type',
        'cancelled_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'appointment_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    // Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Chờ xác nhận',
        self::STATUS_CONFIRMED => 'Đã xác nhận',
        self::STATUS_PROCESSING => 'Đang chăm sóc',
        self::STATUS_COMPLETED => 'Hoàn thành',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    public const STATUS_COLORS = [
        self::STATUS_PENDING => 'warning',
        self::STATUS_CONFIRMED => 'info',
        self::STATUS_PROCESSING => 'primary',
        self::STATUS_COMPLETED => 'success',
        self::STATUS_CANCELLED => 'danger',
    ];

    public const SERVICE_MODES = [
        'at_store' => 'Tại cửa hàng',
        'at_home' => 'Tại nhà',
        'pickup' => 'Nhận thú qua nhân viên shop',
    ];

    public const BOOKING_TYPES = [
        'standard' => 'Tiêu chuẩn',
        'pickup' => 'Giao nhận',
        'event' => 'Sự kiện',
        'boarding' => 'Nhận giữ',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot(['quantity', 'unit_price', 'line_total'])
            ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function images()
    {
        return $this->hasMany(PetProgressImage::class);
    }

    public function logs()
    {
        return $this->hasMany(BookingStatusLog::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function pickupRequest()
    {
        return $this->hasOne(PickupRequest::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Scopes
    public function scopeUpcoming($query, int $hours = 24)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            ->whereBetween('appointment_at', [now(), now()->addHours($hours)]);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByStaff($query, int $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_at', [$startDate, $endDate]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getServiceModeLabelAttribute(): string
    {
        return self::SERVICE_MODES[$this->service_mode] ?? $this->service_mode;
    }

    public function getBookingTypeLabelAttribute(): string
    {
        return self::BOOKING_TYPES[$this->booking_type] ?? $this->booking_type;
    }

    /**
     * Check if booking is paid
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if booking is upcoming
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->status === 'pending' || $this->status === 'confirmed';
    }

    /**
     * Calculate duration in minutes from services
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->services->sum('duration_minutes');
    }

    // Notification methods (giữ nguyên)
    public function notifyStatusChange(string $oldStatus, string $newStatus): void
    {
        \App\Models\UserNotification::sendStatusUpdate($this, $oldStatus, $newStatus);
    }

    public function notifyStaffAssigned(): void
    {
        \App\Models\UserNotification::sendStaffAssigned($this);
    }

    public function notifyConfirmation(): void
    {
        \App\Models\UserNotification::sendBookingConfirmation($this);
    }

    public function notifyConfirmed(): void
    {
        \App\Models\UserNotification::sendBookingConfirmed($this);
    }

    public function notifyPetImageUploaded(int $count): void
    {
        \App\Models\UserNotification::sendPetImageUploaded($this, $count);
    }

    public function notifyReminder(): void
    {
        \App\Models\UserNotification::sendBookingReminder($this);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'link',
        'icon',
        'color',
        'is_read',
        'read_at',
        'scheduled_at',
        'priority',
        'expires_at',
        'category',
        'deleted_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'expires_at' => 'datetime',
        'priority' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: UserNotification thuộc về user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    /**
     * Mark as unread
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false, 'read_at' => null]);
    }

    /**
     * Scope: unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false)->whereNull('deleted_at');
    }

    /**
     * Scope: read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true)->whereNull('deleted_at');
    }

    /**
     * Scope: notifications by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: high priority notifications
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 1);
    }

    /**
     * Scope: expired notifications
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')->where('expires_at', '<', now());
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            1 => 'Cao',
            2 => 'Trung bình',
            3 => 'Thấp',
            default => 'Không xác định',
        };
    }

    /**
     * Check if notification is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    // Static send methods (same as old Notification)
    public static function send($userId, string $type, string $title, string $content, ?string $link = null, ?string $icon = null, ?string $color = null): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'link' => $link,
            'icon' => $icon ?? 'bell',
            'color' => $color ?? 'info',
        ]);
    }

    public static function sendBookingConfirmation(Booking $booking): void
    {
        $link = route('customer.bookings.show', $booking->id);
        self::send(
            $booking->user_id,
            'booking_confirmation',
            'Xác nhận đặt lịch thành công',
            "Lịch hẹn #{$booking->booking_code} của bạn đã được ghi nhận. Vui lòng đến đúng giờ.",
            $link,
            'check-circle',
            'success'
        );
    }

    public static function sendBookingConfirmed(Booking $booking): void
    {
        $link = route('customer.bookings.show', $booking->id);
        self::send(
            $booking->user_id,
            'booking_confirmed',
            'Lịch hẹn đã được xác nhận',
            "Lịch hẹn #{$booking->booking_code} đã được xác nhận và sẽ bắt đầu vào lúc " . $booking->appointment_at->format('H:i d/m/Y') . ".",
            $link,
            'calendar-check',
            'success'
        );
    }

    public static function sendBookingReminder(Booking $booking): void
    {
        $link = route('customer.bookings.show', $booking->id);
        $hoursUntil = $booking->appointment_at->diffInHours(now());

        if ($hoursUntil > 0 && $hoursUntil <= 24) {
            self::send(
                $booking->user_id,
                'booking_reminder',
                'Nhắc lịch hẹn ngày mai',
                "Ngày mai bạn có lịch hẹn #{$booking->booking_code} lúc " . $booking->appointment_at->format('H:i') . ". Vui lòng đến đúng giờ.",
                $link,
                'clock',
                'warning'
            );
        } elseif ($hoursUntil > 24 && $hoursUntil <= 48) {
            self::send(
                $booking->user_id,
                'booking_reminder',
                'Nhắc lịch hẹn trong 2 ngày',
                "Bạn có lịch hẹn #{$booking->booking_code} vào " . $booking->appointment_at->format('H:i d/m/Y') . ". Đừng quên nhé!",
                $link,
                'clock',
                'info'
            );
        }
    }

    public static function sendStatusUpdate(Booking $booking, string $oldStatus, string $newStatus): void
    {
        $link = route('customer.bookings.show', $booking->id);
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang chăm sóc',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
        self::send(
            $booking->user_id,
            'status_update',
            'Cập nhật trạng thái lịch hẹn',
            "Lịch hẹn #{$booking->booking_code} đã chuyển từ '{$statusLabels[$oldStatus]}' sang '{$statusLabels[$newStatus]}'.",
            $link,
            'refresh',
            'info'
        );
    }

    public static function sendPetImageUploaded(Booking $booking, int $imageCount): void
    {
        $link = route('customer.bookings.show', $booking->id);
        self::send(
            $booking->user_id,
            'pet_image',
            'Hình ảnh thú cưng đã được cập nhật',
            "Nhân viên đã upload {$imageCount} hình ảnh tiến độ chăm sóc {$booking->pet->name}.",
            $link,
            'camera',
            'primary'
        );
    }

    public static function sendStaffAssigned(Booking $booking): void
    {
        $link = route('customer.bookings.show', $booking->id);
        $staffName = $booking->staff->full_name ?? 'Nhân viên';
        self::send(
            $booking->user_id,
            'staff_assigned',
            'Nhân viên được phân công',
            "Lịch hẹn #{$booking->booking_code} đã được giao cho {$staffName} phụ trách.",
            $link,
            'user',
            'success'
        );
    }
}

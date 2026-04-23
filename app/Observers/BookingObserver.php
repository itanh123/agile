<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        // Đảm bảo booking được tạo với trạng thái pending
        if (is_null($booking->status)) {
            $booking->status = Booking::STATUS_PENDING;
            $booking->saveQuietly();
        }
    }

    public function updating(Booking $booking): void
    {
        // Theo dõi thay đổi trạng thái để gửi notification
        if ($booking->isDirty('status')) {
            $oldStatus = $booking->getOriginal('status');
            $newStatus = $booking->status;

            // Cập nhật payment_status khi hoàn thành
            if ($newStatus === Booking::STATUS_COMPLETED && $booking->payment_status === 'unpaid') {
                $booking->payment_status = 'paid';
            }
        }
    }
}
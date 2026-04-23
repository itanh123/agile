<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Gửi thông báo nhắc lịch hẹn cho khách hàng';

    public function handle(): int
    {
        $this->info('Bắt đầu gửi thông báo nhắc lịch...');

        $upcomingBookings = Booking::with('user', 'pet')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('appointment_at', [now(), now()->addHours(48)])
            ->get();

        $count = 0;
        foreach ($upcomingBookings as $booking) {
            Notification::sendBookingReminder($booking);
            $count++;
            $this->line("Đã gửi nhắc lịch cho booking #{$booking->booking_code}");
        }

        $this->info("Hoàn tất! Đã gửi {$count} thông báo.");
        return Command::SUCCESS;
    }
}
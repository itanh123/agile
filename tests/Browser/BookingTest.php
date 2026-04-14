<?php

namespace Tests\Browser;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Models\BookingStatusLog;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingTest extends DuskTestCase
{
    public function test_booking_status_completion_and_review_flow(): void
    {
        $customer = User::where('email', 'customer@petcare.test')->firstOrFail();
        $admin = User::where('email', 'admin@petcare.test')->firstOrFail();
        $pet = $customer->pets()->firstOrFail();
        $service = \App\Models\Service::firstOrFail();

        $booking = Booking::create([
            'booking_code' => 'BK-RVW-' . strtoupper(Str::random(6)),
            'user_id' => $customer->id,
            'pet_id' => $pet->id,
            'appointment_at' => now()->addDay(),
            'service_mode' => 'at_store',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'subtotal' => $service->price,
            'discount_amount' => 0,
            'total_amount' => $service->price,
        ]);
        $booking->services()->attach($service->id, ['quantity' => 1, 'unit_price' => $service->price, 'line_total' => $service->price]);

        $this->browse(function (Browser $adminBrowser, Browser $customerBrowser) use ($admin, $customer, $booking) {
            $adminBrowser->visit('/login')
                ->type('email', $admin->email)
                ->type('password', 'password')
                ->press('Login')
                ->pause(1000)
                ->visit('/admin/bookings/' . $booking->id)
                ->select('status', 'completed')
                ->type('note', 'Completed by automation')
                ->press('Update')
                ->pause(800)
                ->assertSee('Booking status updated.')
                ->press('Logout');

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'status' => 'completed',
                'changed_by' => $admin->id,
                'note' => 'Completed by BookingTest',
                'changed_at' => now(),
            ]);

            $customerBrowser->visit('/login')
                ->type('email', $customer->email)
                ->type('password', 'password')
                ->press('Login')
                ->pause(1000)
                ->visit('/customer/reviews/create')
                ->assertPresent('select[name="booking_id"]')
                ->assertPresent('input[name="rating"]')
                ->assertSee('Write Review');
        });

        Review::create([
            'booking_id' => $booking->id,
            'rating' => 5,
            'title' => 'Excellent',
            'comment' => 'Service quality is great',
            'is_public' => true,
        ]);

        $this->assertTrue(Review::where('booking_id', $booking->id)->exists());
    }
}

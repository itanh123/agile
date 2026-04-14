<?php

namespace Tests\Browser;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StaffTest extends DuskTestCase
{
    public function test_staff_can_process_assigned_booking(): void
    {
        $staff = User::where('email', 'staff@petcare.test')->firstOrFail();
        $customer = User::where('email', 'customer@petcare.test')->firstOrFail();
        $pet = $customer->pets()->firstOrFail();
        $service = Service::firstOrFail();

        $booking = Booking::create([
            'booking_code' => 'BK-STF-' . strtoupper(Str::random(6)),
            'user_id' => $customer->id,
            'pet_id' => $pet->id,
            'staff_id' => $staff->id,
            'appointment_at' => now()->addDay(),
            'service_mode' => 'at_store',
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'subtotal' => $service->price,
            'discount_amount' => 0,
            'total_amount' => $service->price,
        ]);
        $booking->services()->attach($service->id, ['quantity' => 1, 'unit_price' => $service->price, 'line_total' => $service->price]);

        $this->browse(function (Browser $browser) use ($staff, $booking) {
            $browser->visit('/login')
                ->type('email', $staff->email)
                ->type('password', 'password')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/staff/dashboard')
                ->clickLink('Assigned Bookings')
                ->assertSee($booking->booking_code)
                ->clickLink('View')
                ->assertSee($booking->booking_code)
                ->assertPresent('select[name="status"]')
                ->assertPresent('input[name="image_path"]')
                ->assertPresent('textarea[name="note"]');
        });
    }
}

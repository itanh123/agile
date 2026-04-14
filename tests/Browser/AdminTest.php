<?php

namespace Tests\Browser;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminTest extends DuskTestCase
{
    public function test_admin_can_manage_service_promotion_and_assign_staff(): void
    {
        $admin = User::where('email', 'admin@petcare.test')->firstOrFail();
        $staff = User::where('email', 'staff@petcare.test')->firstOrFail();
        $customer = User::where('email', 'customer@petcare.test')->firstOrFail();
        $pet = $customer->pets()->firstOrFail();
        $service = Service::firstOrFail();

        $booking = Booking::create([
            'booking_code' => 'BK-ADM-' . strtoupper(Str::random(6)),
            'user_id' => $customer->id,
            'pet_id' => $pet->id,
            'appointment_at' => now()->addDays(2),
            'service_mode' => 'at_store',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'subtotal' => $service->price,
            'discount_amount' => 0,
            'total_amount' => $service->price,
        ]);
        $booking->services()->attach($service->id, ['quantity' => 1, 'unit_price' => $service->price, 'line_total' => $service->price]);

        $this->browse(function (Browser $browser) use ($admin, $staff, $booking) {
            $browser->visit('/login')
                ->type('email', $admin->email)
                ->type('password', 'password')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->visit('/admin/services/create')
                ->type('name', 'Dusk Service ' . time())
                ->select('service_type', 'spa')
                ->type('duration_minutes', '45')
                ->type('price', '333000')
                ->type('description', 'Created by admin dusk test')
                ->press('Save')
                ->pause(800)
                ->assertSee('Service created.')
                ->visit('/admin/promotions/create')
                ->type('code', 'DSK' . rand(100, 999))
                ->type('title', 'Dusk Promotion')
                ->select('discount_type', 'percent')
                ->type('discount_value', '15')
                ->type('start_at', now()->format('Y-m-d\TH:i'))
                ->type('end_at', now()->addDays(3)->format('Y-m-d\TH:i'))
                ->type('description', 'Promo created by admin')
                ->press('Save')
                ->pause(800)
                ->assertSee('Promotion created.')
                ->visit('/admin/bookings')
                ->assertSee($booking->booking_code)
                ->clickLink('Detail')
                ->select('staff_id', (string) $staff->id)
                ->press('Assign')
                ->pause(800)
                ->assertSee('Staff assigned.');
        });
    }
}

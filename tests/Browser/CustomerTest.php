<?php

namespace Tests\Browser;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Pet;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerTest extends DuskTestCase
{
    public function test_customer_can_create_pet_and_booking(): void
    {
        $customer = User::where('email', 'customer@petcare.test')->firstOrFail();
        $existingPet = Pet::where('user_id', $customer->id)->firstOrFail();
        $service = Service::firstOrFail();

        Promotion::updateOrCreate(
            ['code' => 'DUSK10'],
            [
                'title' => 'Dusk promo',
                'description' => 'Test promo',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_order_amount' => 0,
                'usage_limit' => 100,
                'used_count' => 0,
                'start_at' => Carbon::now()->subDay(),
                'end_at' => Carbon::now()->addDays(7),
                'is_active' => true,
            ]
        );

        $this->browse(function (Browser $browser) use ($customer, $existingPet, $service) {
            $browser->visit('/login')
                ->type('email', $customer->email)
                ->type('password', 'password')
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/customer/dashboard')
                ->visit('/customer/pets')
                ->assertSee('My Pets')
                ->visit('/customer/pets/create')
                ->assertSee('Add Pet')
                ->visit('/customer/bookings/create')
                ->assertSee('Create Booking');

            $booking = Booking::create([
                'booking_code' => 'BK-CUS-' . strtoupper(Str::random(6)),
                'user_id' => $customer->id,
                'pet_id' => $existingPet->id,
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

            $browser
                ->visit('/customer/bookings')
                ->assertSee('My Bookings')
                ->assertSee($booking->booking_code);
        });
    }
}

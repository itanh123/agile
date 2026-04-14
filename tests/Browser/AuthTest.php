<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    public function test_user_can_register_login_and_redirect_to_dashboard(): void
    {
        $email = 'dusk_auth_' . time() . '@example.com';
        $password = 'password123';

        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->visit('/')
                ->assertSee('Pet Care')
                ->clickLink('Register')
                ->assertPathIs('/register')
                ->type('full_name', 'Dusk Auth User')
                ->type('email', $email)
                ->type('phone', '0900000000')
                ->type('address', 'Test address')
                ->type('password', $password)
                ->type('password_confirmation', $password)
                ->press('Register')
                ->pause(1000)
                ->assertPathIs('/customer/dashboard')
                ->assertSee('Customer Dashboard')
                ->press('Logout')
                ->pause(500)
                ->assertPathIs('/')
                ->clickLink('Login')
                ->type('email', $email)
                ->type('password', $password)
                ->press('Login')
                ->pause(1000)
                ->assertPathIs('/customer/dashboard');
        });
    }
}

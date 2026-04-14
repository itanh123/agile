<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'payments';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        foreach (SeederState::$bookingIds as $bookingId) {
            DB::table($table)->insert(
                $this->withTimestamps($table, [
                    'booking_id' => $bookingId,
                    'transaction_code' => 'PMT' . $faker->unique()->numerify('######'),
                    'amount' => $faker->randomFloat(2, 20, 500),
                    'status' => $faker->randomElement(['pending', 'paid', 'failed']),
                    'payment_method' => $faker->randomElement(['cash', 'card', 'bank_transfer', 'e_wallet']),
                    'paid_at' => $faker->optional(0.7)->dateTimeBetween('-2 months', 'now'),
                ])
            );
        }
    }
}

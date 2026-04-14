<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'bookings';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $paymentMethods = ['cash', 'card', 'bank_transfer', 'e_wallet'];

        foreach (SeederState::$userIds['all'] ?? [] as $userId) {
            $bookingCount = random_int(1, 2);
            $pets = SeederState::$petIds[$userId] ?? [];
            if ($pets === []) {
                continue;
            }

            for ($i = 0; $i < $bookingCount; $i++) {
                $status = $faker->randomElement($statuses);
                $id = DB::table($table)->insertGetId(
                    $this->withTimestamps($table, [
                        'user_id' => $userId,
                        'pet_id' => $faker->randomElement($pets),
                        'promotion_id' => $faker->optional(0.35)->randomElement(SeederState::$promotionIds ?: [null]),
                        'booking_code' => 'BK' . $faker->unique()->numerify('######'),
                        'booking_time' => $faker->dateTimeBetween('-3 months', '+2 weeks'),
                        'status' => $status,
                        'payment_status' => $status === 'completed' ? 'paid' : $faker->randomElement($paymentStatuses),
                        'payment_method' => $faker->randomElement($paymentMethods),
                        'note' => $faker->optional()->sentence(),
                        'total_amount' => $faker->randomFloat(2, 20, 500),
                    ])
                );

                SeederState::$bookingIds[] = $id;
            }
        }
    }
}

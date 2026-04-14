<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingServicesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'booking_services';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        foreach (SeederState::$bookingIds as $bookingId) {
            $serviceIds = collect(SeederState::$serviceIds)->shuffle()->take(random_int(1, min(3, count(SeederState::$serviceIds))))->all();
            foreach ($serviceIds as $serviceId) {
                DB::table($table)->insert(
                    $this->withTimestamps($table, [
                        'booking_id' => $bookingId,
                        'service_id' => $serviceId,
                        'price' => $faker->randomFloat(2, 15, 220),
                        'quantity' => 1,
                    ])
                );
            }
        }
    }
}

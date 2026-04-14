<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingStatusLogsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'booking_status_logs';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $flow = ['pending', 'confirmed', 'in_progress', 'completed'];

        foreach (SeederState::$bookingIds as $bookingId) {
            $steps = random_int(2, count($flow));
            $statuses = array_slice($flow, 0, $steps);

            foreach ($statuses as $index => $status) {
                DB::table($table)->insert(
                    $this->withTimestamps($table, [
                        'booking_id' => $bookingId,
                        'status' => $status,
                        'changed_by' => $faker->optional(0.8)->randomElement(SeederState::$userIds['staff'] ?? SeederState::$userIds['admin'] ?? []),
                        'note' => $index === 0 ? 'Booking created' : $faker->sentence(),
                        'changed_at' => $faker->dateTimeBetween('-2 months', 'now'),
                    ])
                );
            }
        }
    }
}

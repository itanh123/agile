<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'reviews';
        if (! $this->tableExists($table) || ! $this->tableExists('bookings')) {
            return;
        }

        $faker = fake();
        $completedBookings = $this->hasColumn('bookings', 'status')
            ? DB::table('bookings')->where('status', 'completed')->pluck('id')->all()
            : SeederState::$bookingIds;

        foreach ($completedBookings as $bookingId) {
            DB::table($table)->insert(
                $this->withTimestamps($table, [
                    'booking_id' => $bookingId,
                    'user_id' => $faker->randomElement(SeederState::$userIds['user'] ?? SeederState::$userIds['all'] ?? []),
                    'rating' => $faker->numberBetween(4, 5),
                    'comment' => $faker->paragraph(),
                ])
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetProgressImagesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'pet_progress_images';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        foreach (SeederState::$bookingIds as $bookingId) {
            $count = random_int(1, 3);
            for ($i = 0; $i < $count; $i++) {
                DB::table($table)->insert(
                    $this->withTimestamps($table, [
                        'booking_id' => $bookingId,
                        'image_url' => $faker->imageUrl(1280, 720, 'animals', true),
                        'caption' => $faker->optional()->sentence(),
                        'taken_at' => $faker->dateTimeBetween('-2 months', 'now'),
                    ])
                );
            }
        }
    }
}

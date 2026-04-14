<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'services';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        foreach (['Grooming', 'Vaccination', 'Spa', 'Checkup', 'Surgery'] as $name) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $faker->sentence(),
                    'duration_minutes' => $faker->randomElement([30, 45, 60, 90, 120]),
                    'price' => $faker->randomFloat(2, 15, 300),
                    'is_active' => true,
                ])
            );

            SeederState::$serviceIds[] = $id;
        }
    }
}

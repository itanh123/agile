<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'pets';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $breedMap = [
            'Dog' => ['Husky', 'Poodle'],
            'Cat' => ['Persian', 'British Shorthair'],
        ];

        foreach (SeederState::$userIds['all'] ?? [] as $userId) {
            $count = random_int(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $category = $faker->randomElement(['Dog', 'Cat']);
                $breed = $faker->randomElement($breedMap[$category]);

                $id = DB::table($table)->insertGetId(
                    $this->withTimestamps($table, [
                        'user_id' => $userId,
                        'pet_category_id' => SeederState::$petCategoryIds[$category] ?? null,
                        'pet_breed_id' => SeederState::$petBreedIds[$breed] ?? null,
                        'name' => $faker->firstName(),
                        'gender' => $faker->randomElement(['male', 'female']),
                        'weight' => $faker->randomFloat(1, 2, 35),
                        'date_of_birth' => $faker->dateTimeBetween('-8 years', '-6 months'),
                        'notes' => $faker->optional()->sentence(),
                    ])
                );

                SeederState::$petIds[$userId][] = $id;
                SeederState::$petIds['all'][] = $id;
            }
        }
    }
}

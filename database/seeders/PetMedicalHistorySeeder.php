<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetMedicalHistorySeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'pet_medical_history';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        foreach (SeederState::$petIds['all'] ?? [] as $petId) {
            $entries = random_int(1, 2);
            for ($i = 0; $i < $entries; $i++) {
                DB::table($table)->insert(
                    $this->withTimestamps($table, [
                        'pet_id' => $petId,
                        'diagnosis' => $faker->randomElement(['Routine check', 'Skin irritation', 'Digestive issue', 'Vaccination follow-up']),
                        'treatment' => $faker->sentence(),
                        'veterinarian' => 'Dr. ' . $faker->lastName(),
                        'recorded_at' => $faker->dateTimeBetween('-1 year', 'now'),
                        'notes' => $faker->paragraph(),
                    ])
                );
            }
        }
    }
}

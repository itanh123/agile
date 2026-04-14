<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PetBreedsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'pet_breeds';
        if (! $this->tableExists($table)) {
            return;
        }

        $breeds = [
            'Dog' => ['Husky', 'Poodle'],
            'Cat' => ['Persian', 'British Shorthair'],
        ];

        foreach ($breeds as $category => $list) {
            foreach ($list as $breed) {
                $id = DB::table($table)->insertGetId(
                    $this->withTimestamps($table, [
                        'pet_category_id' => SeederState::$petCategoryIds[$category] ?? null,
                        'name' => $breed,
                        'slug' => Str::slug($breed),
                    ])
                );

                SeederState::$petBreedIds[$breed] = $id;
            }
        }
    }
}

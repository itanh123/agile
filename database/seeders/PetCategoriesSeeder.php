<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetCategoriesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'pet_categories';
        if (! $this->tableExists($table)) {
            return;
        }

        foreach (['Dog', 'Cat'] as $category) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $category,
                    'slug' => strtolower($category),
                ])
            );

            SeederState::$petCategoryIds[$category] = $id;
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetCategory;
use App\Models\PetBreed;

class PetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Chó',
                'slug' => 'dog',
                'description' => 'Thú cưng loại chó',
                'is_active' => true,
            ],
            [
                'name' => 'Mèo',
                'slug' => 'cat',
                'description' => 'Thú cưng loại mèo',
                'is_active' => true,
            ],
            [
                'name' => 'Thỏ',
                'slug' => 'rabbit',
                'description' => 'Thú cưng loại thỏ',
                'is_active' => true,
            ],
            [
                'name' => 'Chuột Hamster',
                'slug' => 'hamster',
                'description' => 'Thú cưng loại hamster',
                'is_active' => true,
            ],
            [
                'name' => 'Chim',
                'slug' => 'bird',
                'description' => 'Thú cưng loại chim',
                'is_active' => true,
            ],
            [
                'name' => 'Cá',
                'slug' => 'fish',
                'description' => 'Thú cưng loại cá',
                'is_active' => true,
            ],
            [
                'name' => 'Rùa',
                'slug' => 'turtle',
                'description' => 'Thú cưng loại rùa',
                'is_active' => true,
            ],
            [
                'name' => 'Khác',
                'slug' => 'other',
                'description' => 'Loại thú cưng khác',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            PetCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Seeded ' . count($categories) . ' pet categories');

        // Seed breeds for dogs and cats
        $this->seedBreeds();
    }

    /**
     * Seed common breeds for dogs and cats
     */
    private function seedBreeds(): void
    {
        $dogCategory = PetCategory::where('slug', 'dog')->first();
        $catCategory = PetCategory::where('slug', 'cat')->first();

        if ($dogCategory) {
            $dogBreeds = [
                ['name' => 'Poodle', 'slug' => 'poodle', 'description' => 'Chó Poodle'],
                ['name' => 'Husky', 'slug' => 'husky', 'description' => 'Chó Husky Siberia'],
                ['name' => 'Golden Retriever', 'slug' => 'golden-retriever', 'description' => 'Chó Golden Retriever'],
                ['name' => 'Corgi', 'slug' => 'corgi', 'description' => 'Chó Corgi'],
                ['name' => 'Beagle', 'slug' => 'beagle', 'description' => 'Chó Beagle'],
                ['name' => 'Chihuahua', 'slug' => 'chihuahua', 'description' => 'Chó Chihuahua'],
                ['name' => 'Pomeranian', 'slug' => 'pomeranian', 'description' => 'Chó Pomeranian'],
                ['name' => 'Samoyed', 'slug' => 'samoyed', 'description' => 'Chó Samoyed'],
                ['name' => 'Mixed', 'slug' => 'mixed-breed', 'description' => 'Chó lai - không rõ nguồn gốc'],
            ];

            foreach ($dogBreeds as $breed) {
                PetBreed::firstOrCreate(
                    ['category_id' => $dogCategory->id, 'slug' => $breed['slug']],
                    $breed
                );
            }

            $this->command->info('✅ Seeded ' . count($dogBreeds) . ' dog breeds');
        }

        if ($catCategory) {
            $catBreeds = [
                ['name' => 'Persian', 'slug' => 'persian', 'description' => 'Mèo Persian'],
                ['name' => 'Siamese', 'slug' => 'siamese', 'description' => 'Mèo Siamese'],
                ['name' => 'Maine Coon', 'slug' => 'maine-coon', 'description' => 'Mèo Maine Coon'],
                ['name' => 'British Shorthair', 'slug' => 'british-shorthair', 'description' => 'Mèo British Shorthair'],
                ['name' => 'Scottish Fold', 'slug' => 'scottish-fold', 'description' => 'Mèo Scottish Fold'],
                ['name' => 'Ragdoll', 'slug' => 'ragdoll', 'description' => 'Mèo Ragdoll'],
                ['name' => 'Bengal', 'slug' => 'bengal', 'description' => 'Mèo Bengal'],
                ['name' => 'Munchkin', 'slug' => 'munchkin', 'description' => 'Mèo Munchkin'],
                ['name' => 'Mixed', 'slug' => 'mixed-breed', 'description' => 'Mèo lai - không rõ nguồn gốc'],
            ];

            foreach ($catBreeds as $breed) {
                PetBreed::firstOrCreate(
                    ['category_id' => $catCategory->id, 'slug' => $breed['slug']],
                    $breed
                );
            }

            $this->command->info('✅ Seeded ' . count($catBreeds) . ' cat breeds');
        }
    }
}

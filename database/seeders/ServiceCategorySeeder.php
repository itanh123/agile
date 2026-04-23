<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Permission;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Làm đẹp & Spa',
                'slug' => 'grooming-spa',
                'description' => 'Dịch vụ làm đẹp, tắm, spa cho thú cưng',
                'icon' => 'sparkles',
                'sort_order' => 1,
            ],
            [
                'name' => 'Y tế & Sức khỏe',
                'slug' => 'healthcare',
                'description' => 'Tiêm phòng, khám bệnh, chăm sóc sức khỏe',
                'icon' => 'heartbeat',
                'sort_order' => 2,
            ],
            [
                'name' => 'Phẫu thuật',
                'slug' => 'surgery',
                'description' => 'Dịch vụ phẫu thuật, can thiệp y tế',
                'icon' => 'scissors',
                'sort_order' => 3,
            ],
            [
                'name' => 'Khám tổng quát',
                'slug' => 'checkup',
                'description' => 'Kiểm tra sức khỏe định kỳ',
                'icon' => 'stethoscope',
                'sort_order' => 4,
            ],
            [
                'name' => 'Dịch vụ khác',
                'slug' => 'other',
                'description' => 'Các dịch vụ khác',
                'icon' => 'ellipsis-h',
                'sort_order' => 99,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}

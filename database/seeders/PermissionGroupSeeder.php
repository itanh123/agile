<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionGroup;
use App\Models\ServiceCategory;
use App\Models\Permission;
use App\Models\Role;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Booking Management',
                'slug' => 'booking',
                'description' => 'Quản lý đặt lịch, booking, appointment',
                'icon' => 'calendar',
                'color' => 'blue',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pet Management',
                'slug' => 'pet',
                'description' => 'Quản lý thú cưng, hồ sơ pet, danh mục',
                'icon' => 'paw',
                'color' => 'green',
                'sort_order' => 2,
            ],
            [
                'name' => 'Service Management',
                'slug' => 'service',
                'description' => 'Quản lý dịch vụ, categories, pricing',
                'icon' => 'brush',
                'color' => 'purple',
                'sort_order' => 3,
            ],
            [
                'name' => 'User Management',
                'slug' => 'user',
                'description' => 'Quản lý người dùng, roles, permissions',
                'icon' => 'users',
                'color' => 'red',
                'sort_order' => 4,
            ],
            [
                'name' => 'Payment Management',
                'slug' => 'payment',
                'description' => 'Quản lý thanh toán, transactions',
                'icon' => 'credit-card',
                'color' => 'orange',
                'sort_order' => 5,
            ],
            [
                'name' => 'Promotion Management',
                'slug' => 'promotion',
                'description' => 'Quản lý khuyến mãi, discounts',
                'icon' => 'tag',
                'color' => 'pink',
                'sort_order' => 6,
            ],
            [
                'name' => 'Report & Analytics',
                'slug' => 'report',
                'description' => 'Báo cáo, thống kê, analytics',
                'icon' => 'chart-bar',
                'color' => 'indigo',
                'sort_order' => 7,
            ],
            [
                'name' => 'System Settings',
                'slug' => 'setting',
                'description' => 'Cài đặt hệ thống, configurations',
                'icon' => 'cog',
                'color' => 'gray',
                'sort_order' => 8,
            ],
        ];

        foreach ($groups as $group) {
            PermissionGroup::firstOrCreate(
                ['slug' => $group['slug']],
                $group
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get permission groups
        $bookingGroup = PermissionGroup::where('slug', 'booking')->first();
        $petGroup = PermissionGroup::where('slug', 'pet')->first();
        $serviceGroup = PermissionGroup::where('slug', 'service')->first();
        $userGroup = PermissionGroup::where('slug', 'user')->first();
        $paymentGroup = PermissionGroup::where('slug', 'payment')->first();
        $promotionGroup = PermissionGroup::where('slug', 'promotion')->first();
        $reportGroup = PermissionGroup::where('slug', 'report')->first();
        $settingGroup = PermissionGroup::where('slug', 'setting')->first();

        $permissions = [
            // BOOKING PERMISSIONS
            [
                'name' => 'Xem danh sách booking',
                'slug' => 'bookings.view',
                'description' => 'Xem danh sách tất cả booking',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'view',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tạo booking mới',
                'slug' => 'bookings.create',
                'description' => 'Tạo booking mới',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'create',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật booking',
                'slug' => 'bookings.update',
                'description' => 'Sửa thông tin booking',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'update',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xóa booking',
                'slug' => 'bookings.delete',
                'description' => 'Xóa booking (hard delete)',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'delete',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Duyệt booking',
                'slug' => 'bookings.approve',
                'description' => 'Duyệt booking (change status to confirmed)',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'approve',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Phân công nhân viên',
                'slug' => 'bookings.assign',
                'description' => 'Phân công staff cho booking',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'assign',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý booking',
                'slug' => 'bookings.manage',
                'description' => 'Full quyền quản lý booking (CRUD + approve + assign)',
                'module' => 'booking',
                'resource_type' => 'booking',
                'action' => 'manage',
                'group_id' => $bookingGroup?->id,
                'is_active' => true,
            ],

            // PET PERMISSIONS
            [
                'name' => 'Xem danh sách pet',
                'slug' => 'pets.view',
                'description' => 'Xem danh sách pet',
                'module' => 'pet',
                'resource_type' => 'pet',
                'action' => 'view',
                'group_id' => $petGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Thêm pet mới',
                'slug' => 'pets.create',
                'description' => 'Thêm pet mới vào hệ thống',
                'module' => 'pet',
                'resource_type' => 'pet',
                'action' => 'create',
                'group_id' => $petGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật pet',
                'slug' => 'pets.update',
                'description' => 'Sửa thông tin pet',
                'module' => 'pet',
                'resource_type' => 'pet',
                'action' => 'update',
                'group_id' => $petGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xóa pet',
                'slug' => 'pets.delete',
                'description' => 'Xóa pet (soft delete)',
                'module' => 'pet',
                'resource_type' => 'pet',
                'action' => 'delete',
                'group_id' => $petGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý pet',
                'slug' => 'pets.manage',
                'description' => 'Full quyền quản lý pet',
                'module' => 'pet',
                'resource_type' => 'pet',
                'action' => 'manage',
                'group_id' => $petGroup?->id,
                'is_active' => true,
            ],

            // SERVICE PERMISSIONS
            [
                'name' => 'Xem danh sách dịch vụ',
                'slug' => 'services.view',
                'description' => 'Xem danh sách dịch vụ',
                'module' => 'service',
                'resource_type' => 'service',
                'action' => 'view',
                'group_id' => $serviceGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tạo dịch vụ mới',
                'slug' => 'services.create',
                'description' => 'Thêm dịch vụ mới',
                'module' => 'service',
                'resource_type' => 'service',
                'action' => 'create',
                'group_id' => $serviceGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật dịch vụ',
                'slug' => 'services.update',
                'description' => 'Sửa thông tin dịch vụ',
                'module' => 'service',
                'resource_type' => 'service',
                'action' => 'update',
                'group_id' => $serviceGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xóa dịch vụ',
                'slug' => 'services.delete',
                'description' => 'Xóa dịch vụ',
                'module' => 'service',
                'resource_type' => 'service',
                'action' => 'delete',
                'group_id' => $serviceGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý dịch vụ',
                'slug' => 'services.manage',
                'description' => 'Full quyền quản lý dịch vụ',
                'module' => 'service',
                'resource_type' => 'service',
                'action' => 'manage',
                'group_id' => $serviceGroup?->id,
                'is_active' => true,
            ],

            // USER PERMISSIONS
            [
                'name' => 'Xem danh sách người dùng',
                'slug' => 'users.view',
                'description' => 'Xem danh sách user',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'view',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tạo user mới',
                'slug' => 'users.create',
                'description' => 'Tạo user mới',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'create',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật user',
                'slug' => 'users.update',
                'description' => 'Sửa thông tin user',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'update',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xóa user',
                'slug' => 'users.delete',
                'description' => 'Xóa user (soft delete)',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'delete',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý roles & permissions',
                'slug' => 'users.manage-roles',
                'description' => 'Phân công roles, permissions cho user',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'assign',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý user',
                'slug' => 'users.manage',
                'description' => 'Full quyền quản lý user',
                'module' => 'user',
                'resource_type' => 'user',
                'action' => 'manage',
                'group_id' => $userGroup?->id,
                'is_active' => true,
            ],

            // PAYMENT PERMISSIONS
            [
                'name' => 'Xem danh sách thanh toán',
                'slug' => 'payments.view',
                'description' => 'Xem danh sách payment',
                'module' => 'payment',
                'resource_type' => 'payment',
                'action' => 'view',
                'group_id' => $paymentGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xác nhận thanh toán',
                'slug' => 'payments.approve',
                'description' => 'Xác nhận payment thành công',
                'module' => 'payment',
                'resource_type' => 'payment',
                'action' => 'approve',
                'group_id' => $paymentGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Hoàn tiền',
                'slug' => 'payments.refund',
                'description' => 'Hoàn tiền cho booking',
                'module' => 'payment',
                'resource_type' => 'payment',
                'action' => 'refund',
                'group_id' => $paymentGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý thanh toán',
                'slug' => 'payments.manage',
                'description' => 'Full quyền quản lý payment',
                'module' => 'payment',
                'resource_type' => 'payment',
                'action' => 'manage',
                'group_id' => $paymentGroup?->id,
                'is_active' => true,
            ],

            // PROMOTION PERMISSIONS
            [
                'name' => 'Xem khuyến mãi',
                'slug' => 'promotions.view',
                'description' => 'Xem danh sách promotion',
                'module' => 'promotion',
                'resource_type' => 'promotion',
                'action' => 'view',
                'group_id' => $promotionGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tạo promotion',
                'slug' => 'promotions.create',
                'description' => 'Tạo mã khuyến mãi mới',
                'module' => 'promotion',
                'resource_type' => 'promotion',
                'action' => 'create',
                'group_id' => $promotionGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật promotion',
                'slug' => 'promotions.update',
                'description' => 'Sửa promotion',
                'module' => 'promotion',
                'resource_type' => 'promotion',
                'action' => 'update',
                'group_id' => $promotionGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xóa promotion',
                'slug' => 'promotions.delete',
                'description' => 'Xóa promotion',
                'module' => 'promotion',
                'resource_type' => 'promotion',
                'action' => 'delete',
                'group_id' => $promotionGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Quản lý promotion',
                'slug' => 'promotions.manage',
                'description' => 'Full quyền quản lý promotion',
                'module' => 'promotion',
                'resource_type' => 'promotion',
                'action' => 'manage',
                'group_id' => $promotionGroup?->id,
                'is_active' => true,
            ],

            // REPORT PERMISSIONS
            [
                'name' => 'Xem báo cáo doanh thu',
                'slug' => 'reports.revenue',
                'description' => 'Xem báo cáo doanh thu',
                'module' => 'report',
                'resource_type' => 'report',
                'action' => 'view',
                'group_id' => $reportGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xem báo cáo booking',
                'slug' => 'reports.bookings',
                'description' => 'Xem báo cáo booking statistics',
                'module' => 'report',
                'resource_type' => 'report',
                'action' => 'view',
                'group_id' => $reportGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xem báo cáo customer',
                'slug' => 'reports.customers',
                'description' => 'Xem báo cáo customer analytics',
                'module' => 'report',
                'resource_type' => 'report',
                'action' => 'view',
                'group_id' => $reportGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Export reports',
                'slug' => 'reports.export',
                'description' => 'Export báo cáo ra Excel/PDF',
                'module' => 'report',
                'resource_type' => 'report',
                'action' => 'export',
                'group_id' => $reportGroup?->id,
                'is_active' => true,
            ],

            // SETTING PERMISSIONS
            [
                'name' => 'Xem cài đặt hệ thống',
                'slug' => 'settings.view',
                'description' => 'Xem cài đặt hệ thống',
                'module' => 'setting',
                'resource_type' => 'setting',
                'action' => 'view',
                'group_id' => $settingGroup?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cập nhật cài đặt',
                'slug' => 'settings.update',
                'description' => 'Thay đổi cài đặt hệ thống',
                'module' => 'setting',
                'resource_type' => 'setting',
                'action' => 'update',
                'group_id' => $settingGroup?->id,
                'is_active' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('✅ Seeded ' . count($permissions) . ' permissions in ' . PermissionGroup::count() . ' groups');
    }
}

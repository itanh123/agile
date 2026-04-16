<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'permissions';
        if (! $this->tableExists($table)) {
            return;
        }

        $permissions = [
            ['name' => 'booking.view_assigned', 'slug' => 'booking.view_assigned', 'module' => 'booking', 'description' => 'View assigned bookings'],
            ['name' => 'booking.update_status', 'slug' => 'booking.update_status', 'module' => 'booking', 'description' => 'Update booking status'],
            ['name' => 'booking.upload_image', 'slug' => 'booking.upload_image', 'module' => 'booking', 'description' => 'Upload pet progress images'],
            ['name' => 'booking.add_note', 'slug' => 'booking.add_note', 'module' => 'booking', 'description' => 'Add notes to bookings'],
            ['name' => 'booking.assign_staff', 'slug' => 'booking.assign_staff', 'module' => 'booking', 'description' => 'Assign staff to bookings'],
            ['name' => 'staff.view_team', 'slug' => 'staff.view_team', 'module' => 'staff', 'description' => 'View team members'],
            ['name' => 'staff.manage_team', 'slug' => 'staff.manage_team', 'module' => 'staff', 'description' => 'Manage team members'],
            ['name' => 'user.view', 'slug' => 'user.view', 'module' => 'user', 'description' => 'View users'],
            ['name' => 'user.create', 'slug' => 'user.create', 'module' => 'user', 'description' => 'Create users'],
            ['name' => 'user.update', 'slug' => 'user.update', 'module' => 'user', 'description' => 'Update users'],
            ['name' => 'user.delete', 'slug' => 'user.delete', 'module' => 'user', 'description' => 'Delete users'],
            ['name' => 'service.view', 'slug' => 'service.view', 'module' => 'service', 'description' => 'View services'],
            ['name' => 'service.create', 'slug' => 'service.create', 'module' => 'service', 'description' => 'Create services'],
            ['name' => 'service.update', 'slug' => 'service.update', 'module' => 'service', 'description' => 'Update services'],
            ['name' => 'service.delete', 'slug' => 'service.delete', 'module' => 'service', 'description' => 'Delete services'],
            ['name' => 'promotion.view', 'slug' => 'promotion.view', 'module' => 'promotion', 'description' => 'View promotions'],
            ['name' => 'promotion.create', 'slug' => 'promotion.create', 'module' => 'promotion', 'description' => 'Create promotions'],
            ['name' => 'promotion.update', 'slug' => 'promotion.update', 'module' => 'promotion', 'description' => 'Update promotions'],
            ['name' => 'promotion.delete', 'slug' => 'promotion.delete', 'module' => 'promotion', 'description' => 'Delete promotions'],
            ['name' => 'report.view', 'slug' => 'report.view', 'module' => 'report', 'description' => 'View reports'],
        ];

        foreach ($permissions as $permission) {
            $exists = DB::table($table)->where('name', $permission['name'])->exists();
            if ($exists) {
                DB::table($table)->where('name', $permission['name'])->update([
                    'slug' => $permission['slug'],
                    'module' => $permission['module'],
                    'description' => $permission['description'],
                    'updated_at' => now(),
                ]);
            } else {
                $id = DB::table($table)->insertGetId(
                    $this->withTimestamps($table, $permission)
                );
            }
            
            SeederState::$permissionIds[$permission['name']] = DB::table($table)->where('name', $permission['name'])->value('id');
        }
    }
}

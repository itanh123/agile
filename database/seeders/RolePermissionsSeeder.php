<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'role_permissions';
        if (! $this->tableExists($table)) {
            return;
        }

        $bookingPermissions = [
            'booking.view_assigned',
            'booking.update_status',
            'booking.upload_image',
            'booking.add_note',
        ];

        $adminPermissions = array_merge($bookingPermissions, [
            'staff.view_team',
            'staff.manage_team',
            'booking.assign_staff',
        ]);

        $mapping = [
            'admin' => $adminPermissions,
            'staff' => $bookingPermissions,
        ];

        foreach ($mapping as $role => $permissions) {
            $roleId = SeederState::$roleIds[$role] ?? null;
            if (! $roleId) {
                continue;
            }

            foreach ($permissions as $permission) {
                $permissionId = SeederState::$permissionIds[$permission] ?? null;
                if (! $permissionId) {
                    continue;
                }

                DB::table($table)->insert(
                    $this->withTimestamps($table, [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ])
                );
            }
        }
    }
}

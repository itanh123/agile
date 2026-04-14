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

        $mapping = [
            'admin' => ['create', 'read', 'update', 'delete', 'manage'],
            'staff' => ['create', 'read', 'update'],
            'user' => ['read'],
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

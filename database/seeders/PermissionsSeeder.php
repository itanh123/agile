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

        foreach (['create', 'read', 'update', 'delete', 'manage'] as $permission) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $permission,
                    'slug' => $permission,
                    'description' => ucfirst($permission) . ' permission',
                ])
            );

            SeederState::$permissionIds[$permission] = $id;
        }
    }
}

<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'roles';
        if (! $this->tableExists($table)) {
            return;
        }

        $roles = ['admin', 'staff', 'user'];
        foreach ($roles as $role) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $role,
                    'slug' => $role,
                    'description' => ucfirst($role) . ' role',
                ])
            );

            SeederState::$roleIds[$role] = $id;
        }
    }
}

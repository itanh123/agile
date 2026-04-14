<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'users';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $definitions = array_merge(
            [['name' => 'System Admin', 'email' => 'admin@example.com', 'role' => 'admin']],
            collect(range(1, 3))->map(fn ($i) => ['name' => "Staff {$i}", 'email' => "staff{$i}@example.com", 'role' => 'staff'])->all(),
            collect(range(1, 6))->map(fn ($i) => ['name' => $faker->name(), 'email' => "user{$i}@example.com", 'role' => 'user'])->all()
        );

        foreach ($definitions as $definition) {
            $roleName = $definition['role'];
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $definition['name'],
                    'email' => $definition['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => $this->now(),
                    'role_id' => SeederState::$roleIds[$roleName] ?? null,
                    'role' => $roleName,
                ])
            );

            SeederState::$userIds[$roleName][] = $id;
            SeederState::$userIds['all'][] = $id;
        }
    }
}

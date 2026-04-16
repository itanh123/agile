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
        
        $adminId = DB::table($table)->insertGetId(
            $this->withTimestamps($table, [
                'name' => 'Admin',
                'full_name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $this->now(),
                'role_id' => SeederState::$roleIds['admin'] ?? null,
            ])
        );

        $staffIds = [];
        foreach (range(1, 3) as $i) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => "Staff {$i}",
                    'full_name' => "Staff User {$i}",
                    'email' => "staff{$i}@example.com",
                    'password' => Hash::make('password'),
                    'email_verified_at' => $this->now(),
                    'role_id' => SeederState::$roleIds['staff'] ?? null,
                ])
            );
            $staffIds[] = $id;
            SeederState::$userIds['staff'][] = $id;
        }

        if (count($staffIds) >= 2) {
            DB::table($table)->where('id', $staffIds[0])->update(['manager_id' => $staffIds[1]]);
            DB::table($table)->where('id', $staffIds[2])->update(['manager_id' => $staffIds[1]]);
        }

        foreach (range(1, 6) as $i) {
            $id = DB::table($table)->insertGetId(
                $this->withTimestamps($table, [
                    'name' => $faker->name(),
                    'full_name' => $faker->name(),
                    'email' => "user{$i}@example.com",
                    'password' => Hash::make('password'),
                    'email_verified_at' => $this->now(),
                    'role_id' => SeederState::$roleIds['user'] ?? null,
                ])
            );
            SeederState::$userIds['user'][] = $id;
        }

        SeederState::$userIds['admin'][] = $adminId;
        SeederState::$userIds['all'] = array_merge(
            [$adminId],
            $staffIds,
            SeederState::$userIds['user'] ?? []
        );
    }
}

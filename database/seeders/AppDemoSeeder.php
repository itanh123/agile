<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\PetBreed;
use App\Models\PetCategory;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AppDemoSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'description' => 'System admin']);
        $staffRole = Role::firstOrCreate(['slug' => 'staff'], ['name' => 'Staff', 'description' => 'Staff']);
        $customerRole = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer', 'description' => 'Customer']);

        User::firstOrCreate(['email' => 'admin@petcare.test'], [
            'name' => 'Admin',
            'full_name' => 'Admin User',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $staff = User::firstOrCreate(['email' => 'staff@petcare.test'], [
            'name' => 'Staff',
            'full_name' => 'Staff User',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);

        $customer = User::firstOrCreate(['email' => 'customer@petcare.test'], [
            'name' => 'Customer',
            'full_name' => 'Customer User',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
        ]);

        $dog = PetCategory::firstOrCreate(['slug' => 'dog'], ['name' => 'Dog']);
        $cat = PetCategory::firstOrCreate(['slug' => 'cat'], ['name' => 'Cat']);
        $poodle = PetBreed::firstOrCreate(['slug' => 'poodle'], ['category_id' => $dog->id, 'name' => 'Poodle']);
        PetBreed::firstOrCreate(['slug' => 'husky'], ['category_id' => $dog->id, 'name' => 'Husky']);
        PetBreed::firstOrCreate(['slug' => 'persian'], ['category_id' => $cat->id, 'name' => 'Persian']);

        Pet::firstOrCreate(['name' => 'Milo', 'user_id' => $customer->id], [
            'category_id' => $dog->id,
            'breed_id' => $poodle->id,
            'gender' => 'male',
            'weight' => 8,
            'health_status' => 'Good',
        ]);

        foreach ([
            ['Grooming', 'grooming', 200000],
            ['Vaccination', 'vaccination', 250000],
            ['Spa', 'spa', 300000],
            ['Checkup', 'checkup', 150000],
            ['Surgery', 'surgery', 1000000],
        ] as [$name, $type, $price]) {
            Service::firstOrCreate(['name' => $name], [
                'service_type' => $type,
                'price' => $price,
                'duration_minutes' => 60,
                'is_active' => true,
            ]);
        }
    }
}

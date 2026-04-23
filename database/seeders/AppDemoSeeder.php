<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetBreed;
use App\Models\PetCategory;
use App\Models\Permission;
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

        $allPermissions = [
            ['name' => 'booking.view_assigned', 'module' => 'booking', 'description' => 'View assigned bookings'],
            ['name' => 'booking.update_status', 'module' => 'booking', 'description' => 'Update booking status'],
            ['name' => 'booking.upload_image', 'module' => 'booking', 'description' => 'Upload pet progress images'],
            ['name' => 'booking.add_note', 'module' => 'booking', 'description' => 'Add notes to bookings'],
            ['name' => 'booking.assign_staff', 'module' => 'booking', 'description' => 'Assign staff to bookings'],
            ['name' => 'staff.view_team', 'module' => 'staff', 'description' => 'View team members'],
            ['name' => 'staff.manage_team', 'module' => 'staff', 'description' => 'Manage team members'],
            ['name' => 'user.view', 'module' => 'user', 'description' => 'View users'],
            ['name' => 'user.create', 'module' => 'user', 'description' => 'Create users'],
            ['name' => 'user.update', 'module' => 'user', 'description' => 'Update users'],
            ['name' => 'user.delete', 'module' => 'user', 'description' => 'Delete users'],
            ['name' => 'service.view', 'module' => 'service', 'description' => 'View services'],
            ['name' => 'service.create', 'module' => 'service', 'description' => 'Create services'],
            ['name' => 'service.update', 'module' => 'service', 'description' => 'Update services'],
            ['name' => 'service.delete', 'module' => 'service', 'description' => 'Delete services'],
            ['name' => 'promotion.view', 'module' => 'promotion', 'description' => 'View promotions'],
            ['name' => 'promotion.create', 'module' => 'promotion', 'description' => 'Create promotions'],
            ['name' => 'promotion.update', 'module' => 'promotion', 'description' => 'Update promotions'],
            ['name' => 'promotion.delete', 'module' => 'promotion', 'description' => 'Delete promotions'],
            ['name' => 'report.view', 'module' => 'report', 'description' => 'View reports'],
        ];

        foreach ($allPermissions as $perm) {
            // Tách resource và action từ name (vd: booking.view_assigned -> resource: booking, action: view_assigned)
            $parts = explode('.', $perm['name']);
            $resource = $parts[0] ?? $perm['module'];
            $action = $parts[1] ?? 'view';

            Permission::updateOrCreate(['name' => $perm['name']], [
                'slug' => $perm['name'],
                'module' => $perm['module'],
                'resource_type' => $resource,
                'action' => $action,
                'description' => $perm['description'],
                'is_active' => true,
            ]);
        }

        $adminUser = User::firstOrCreate(['email' => 'admin@petcare.test'], [
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

        $staff1 = User::firstOrCreate(['email' => 'staff1@petcare.test'], [
            'name' => 'staff1',
            'full_name' => 'Staff One',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);

        $staff2 = User::firstOrCreate(['email' => 'staff2@petcare.test'], [
            'name' => 'staff2',
            'full_name' => 'Staff Two',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);

        $leader = User::firstOrCreate(['email' => 'leader@petcare.test'], [
            'name' => 'leader',
            'full_name' => 'Team Leader',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);

        $staff1->manager_id = $leader->id;
        $staff1->save();
        $staff2->manager_id = $leader->id;
        $staff2->save();
        $staff->manager_id = $leader->id;
        $staff->save();

        $adminRole->permissions()->sync(Permission::pluck('id')->toArray());

        $staffRole->permissions()->sync(
            Permission::whereIn('name', [
                'booking.view_assigned',
                'booking.update_status',
                'booking.upload_image',
                'booking.add_note',
            ])->pluck('id')->toArray()
        );

        $staff1->directPermissions()->sync([
            Permission::where('name', 'booking.update_status')->first()->id
        ]);

        $staff2->directPermissions()->sync([
            Permission::where('name', 'booking.upload_image')->first()->id,
            Permission::where('name', 'booking.add_note')->first()->id,
        ]);

        $leader->directPermissions()->sync(
            Permission::whereIn('name', [
                'booking.view_assigned',
                'booking.update_status',
                'booking.upload_image',
                'booking.add_note',
                'staff.view_team',
                'staff.manage_team',
                'booking.assign_staff',
            ])->pluck('id')->toArray()
        );

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

        $pet = Pet::firstOrCreate(['name' => 'Milo', 'user_id' => $customer->id], [
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

        $grooming = Service::where('name', 'Grooming')->first();
        Booking::firstOrCreate(
            ['booking_code' => 'BK-LEADER01'],
            [
                'booking_code' => 'BK-LEADER01',
                'user_id' => $customer->id,
                'pet_id' => $pet->id,
                'staff_id' => $leader->id,
                'appointment_at' => now()->addDays(1),
                'status' => 'pending',
                'service_mode' => 'at_store',
                'payment_status' => 'unpaid',
                'payment_method' => 'cash',
                'subtotal' => $grooming->price,
                'discount_amount' => 0,
                'total_amount' => $grooming->price,
            ]
        );
    }
}

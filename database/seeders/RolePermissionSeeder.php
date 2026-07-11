<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'customers.view',
            'customers.manage',
            'vehicles.view',
            'vehicles.manage',
            'mechanics.view',
            'mechanics.manage',
            'inventory.view',
            'inventory.manage',
            'services.view',
            'services.manage',
            'jobs.view',
            'jobs.manage',
            'invoices.view',
            'invoices.manage',
            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::findOrCreate(UserRole::Admin->value, 'web');
        $serviceAdvisor = Role::findOrCreate(UserRole::ServiceAdvisor->value, 'web');
        $mechanic = Role::findOrCreate(UserRole::Mechanic->value, 'web');

        $admin->syncPermissions($permissions);
        $serviceAdvisor->syncPermissions([
            'customers.view',
            'customers.manage',
            'vehicles.view',
            'vehicles.manage',
            'jobs.view',
            'jobs.manage',
            'invoices.view',
            'invoices.manage',
        ]);
        $mechanic->syncPermissions([
            'jobs.view',
        ]);

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@garageflow.test'],
            [
                'name' => 'GarageFlow Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $adminUser->syncRoles([$admin]);

        $advisor = User::updateOrCreate(
            ['email' => 'advisor@garageflow.test'],
            [
                'name' => 'Service Advisor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $advisor->syncRoles([$serviceAdvisor]);

        $mechanicUser = User::updateOrCreate(
            ['email' => 'mechanic@garageflow.test'],
            [
                'name' => 'Mechanic User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
        $mechanicUser->syncRoles([$mechanic]);
    }
}

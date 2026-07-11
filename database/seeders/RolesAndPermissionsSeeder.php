<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create initial permissions
        Permission::firstOrCreate(['name' => 'view dashboard']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage roles']);
        Permission::firstOrCreate(['name' => 'manage companies']);

        // Create roles and assign created permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrator']);
        // Administrator typically bypasses permission checks via Gate, or we give it all
        $roleAdmin->givePermissionTo(Permission::all());

        $roleUser = Role::firstOrCreate(['name' => 'Standard User']);
        $roleUser->givePermissionTo('view dashboard');

        // Assign Admin role to the first user if exists
        $user = User::first();
        if ($user) {
            $user->assignRole('Administrator');
        }
    }
}

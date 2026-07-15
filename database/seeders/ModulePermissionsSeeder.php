<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ModulePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'clients',
            'leads',
            'contacts',
            'projects',
            'tasks',
            'activities',
            'followups',
            'tickets'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Additional specific permissions
        Permission::firstOrCreate(['name' => 'manage crm']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'manage files']);

        // Assign all permissions to Administrator role
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrator']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}

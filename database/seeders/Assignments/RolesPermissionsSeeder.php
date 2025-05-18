<?php

namespace Database\Seeders\Assignments;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin Role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin Role
        $adminRole = Role::where('name', 'admin')->first();
        // $adminPermissions = Permission::whereNotIn('name', ['users_delete'])->pluck('id')->toArray();
        $adminRole->givePermissionTo('dashboard_access');
    }
}

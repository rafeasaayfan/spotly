<?php

namespace Database\Seeders\Tables\Assignments;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Privilege Permission
        Permission::create([
            'name' => 'assignments_access',
            'guard_name' => 'web',
            'description' => 'Control to the all dashboard with assignments',
        ]);

        // Dashboard Permission
        Permission::create([
            'name' => 'dashboard_access',
            'guard_name' => 'web',
            'description' => 'Control to the all dashboard without the roles and permissions',
        ]);
    }
}

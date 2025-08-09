<?php

namespace Database\Seeders\Tables\Assignments;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin Role that can manage all users and the access control
        Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'description' => 'Control to the all website',
        ]);

        // Admin Role that can manage all users
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
            'description' => 'Control to the all website without the roles & permissions',
        ]);
    }
}

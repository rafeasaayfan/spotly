<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Tables\Assignments\PermissionsSeeder;
use Database\Seeders\Tables\Assignments\RolesPermissionsSeeder;
use Database\Seeders\Tables\Assignments\RolesSeeder;
use Database\Seeders\Tables\WebsiteTypesSeeder;
use Database\Seeders\Tables\TemplateColorsSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'rafehsaayfan@gmail.com',
        ])->assignRole('super_admin');

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'rafe3souayfan@gmail.com',
        ])->assignRole('admin');

        $this->call([
            WebsiteTypesSeeder::class,
            TemplateColorsSeeder::class,
        ]);

        User::factory(1000)->create();
    }
}

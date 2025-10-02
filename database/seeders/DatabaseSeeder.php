<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Tables\Assignments\PermissionSeeder;
use Database\Seeders\Tables\Assignments\RolePermissionSeeder;
use Database\Seeders\Tables\Assignments\RoleSeeder;
use Database\Seeders\Tables\PaymentMethodSeeder;
use Database\Seeders\Tables\PlanSeeder;
use Database\Seeders\Tables\WebsiteTypeSeeder;
use Database\Seeders\Tables\TemplateColorSeeder;
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
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
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
            WebsiteTypeSeeder::class,
            TemplateColorSeeder::class,
            PlanSeeder::class,
            PaymentMethodSeeder::class
        ]);

        User::factory(1000)->create();
    }
}

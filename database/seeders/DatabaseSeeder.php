<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Tables\Assignments\PermissionSeeder;
use Database\Seeders\Tables\Assignments\RolePermissionSeeder;
use Database\Seeders\Tables\Assignments\RoleSeeder;
use Database\Seeders\Tables\ColorSeeder;
use Database\Seeders\Tables\PaymentMethodSeeder;
use Database\Seeders\Tables\PlanSeeder;
use Database\Seeders\Tables\WebsiteTypeSeeder;
use Database\Seeders\Tables\TemplateColorSeeder;
use Database\Seeders\Tables\TemplateSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            'email_verified_at' => now(),
            'password' => Hash::make('RS7$2.0.0.3RS7$'),
            'remember_token' => Str::random(10),
        ])->assignRole('super_admin');

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'rafe3souayfan@gmail.com',
            'password' => Hash::make('Servusrafehfcb7$'),
            'remember_token' => Str::random(10),
        ])->assignRole('admin');

        $this->call([
            WebsiteTypeSeeder::class,
            TemplateColorSeeder::class,
            TemplateSeeder::class,
            PlanSeeder::class,
            PaymentMethodSeeder::class,
            ColorSeeder::class,
        ]);

        // User::factory(1000)->create();
    }
}

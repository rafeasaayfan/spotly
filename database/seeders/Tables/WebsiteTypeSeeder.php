<?php

namespace Database\Seeders\Tables;

use Illuminate\Database\Seeder;
use App\Models\WebsiteType;

class WebsiteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WebsiteType::create([
            'created_by' => 1,
            'title' => 'E-commerce',
            'type' => 'e-commerce',
            'description' => 'E-commerce website for selling products and services to customers',
            'priority' => 1,
            'is_active' => true,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Restaurant',
            'type' => 'restaurant',
            'description' => 'Restaurant website for showcasing menu and services',
            'priority' => 2,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Portfolio',
            'type' => 'portfolio',
            'description' => 'Portfolio website for showcasing projects and services',
            'priority' => 3,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Blog & News',
            'type' => 'blog-news',
            'description' => 'Blog and news website for sharing articles and news',
            'priority' => 4,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Clinic',
            'type' => 'clinic',
            'description' => 'Clinic website for showcasing services and appointments',
            'priority' => 5,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Gym',
            'type' => 'gym',
            'description' => 'Gym website for showcasing services and appointments',
            'priority' => 6,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Hotel',
            'type' => 'hotel',
            'description' => 'Hotel website for showcasing services and appointments',
            'priority' => 7,
            'is_active' => false,
        ]);

        WebsiteType::create([
            'created_by' => 1,
            'title' => 'Lawyer',
            'type' => 'lawyer',
            'description' => 'Lawyer website for showcasing services and appointments',
            'priority' => 8,
            'is_active' => false,
        ]);
    }
}

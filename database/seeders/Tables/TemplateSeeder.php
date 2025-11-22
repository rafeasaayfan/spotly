<?php

namespace Database\Seeders\Tables;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Template::create([
            'created_by' => 1,
            'name' => 'spotly-ui',
            'description' => 'Our spotly template',
            'is_active' => 1,
        ]);
    }
}

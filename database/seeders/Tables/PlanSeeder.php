<?php

namespace Database\Seeders\Tables;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Free Trial',
            'price' => '0',
            'currency' => 'USD',
            'duration' => 'three days',
            'features' => [
                'Enjoy a 3-day free trial with full access',
            ],
            'is_active' => 1,
        ]);

        Plan::create([
            'website_type_id' => 1,
            'name' => 'Monthly',
            'price' => '15',
            'currency' => 'USD',
            'duration' => 'monthly',
            'features' => [
                'Enjoy a 3-day free trial with full access',
                'Dedicated Spotly customer support',
                'Seamless ordering via WhatsApp integration',
                'Enable customers to create and manage wish orders',
            ],
            'is_active' => 1,
        ]);

        Plan::create([
            'website_type_id' => 1,
            'name' => 'Yearly',
            'price' => '170',
            'currency' => 'USD',
            'duration' => 'yearly',
            'features' => [
                'Enjoy a 3-day free trial with full access',
                'Dedicated Spotly customer support',
                'Seamless ordering via WhatsApp integration',
                'Enable customers to create and manage wish orders',
            ],
            'is_active' => 1,
        ]);
    }
}

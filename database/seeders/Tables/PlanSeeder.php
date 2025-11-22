<?php

namespace Database\Seeders\Tables;

use App\Enums\Spotly\PlanCurrency;
use App\Enums\Spotly\PlanDuration;
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
            'currency' => PlanCurrency::USD->value,
            'duration' => PlanDuration::FREE_THREE_DAYS->value,
            'features' => [
                'Enjoy a 3-day free trial with full access',
            ],
            'is_active' => 1,
        ]);

        Plan::create([
            'website_type_id' => 1,
            'name' => 'Monthly',
            'price' => '15',
            'currency' => PlanCurrency::USD->value,
            'duration' => PlanDuration::MONTHLY->value,
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
            'currency' => PlanCurrency::USD->value,
            'duration' => PlanDuration::YEARLY->value,
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

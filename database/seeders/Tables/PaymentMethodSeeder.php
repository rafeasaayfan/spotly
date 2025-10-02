<?php

namespace Database\Seeders\Tables;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Cash On Delivery',
            'code' => 'COD',
            'description' => 'Pay with cash upon delivery',
            'settings' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}

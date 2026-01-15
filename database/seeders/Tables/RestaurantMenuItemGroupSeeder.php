<?php

namespace Database\Seeders\Tables;

use App\Enums\Websites\Restaurant\MenuItemGroupPriceType;
use App\Models\RestaurantMenuItemGroup;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantMenuItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantMenuItemGroup::create([
            'name' => 'Size',
            'name_ar' => 'الحجم',

            'is_required_for_client' => true,

            'max_select' => 1,

            'price_type' => MenuItemGroupPriceType::CUSTOM->value
        ]);

        RestaurantMenuItemGroup::create([
            'name' => 'Extras',
            'name_ar' => 'إضافات',
        ]);

        RestaurantMenuItemGroup::create([
            'name' => 'Remove Ingredients',
            'name_ar' => 'بدون مكونات',

            'price_type' => MenuItemGroupPriceType::DEACREASE->value
        ]);
    }
}

<?php

use App\Enums\Websites\Restaurant\MenuItemGroupPriceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurant_menu_item_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('name_ar');

            $table->boolean('is_required_for_admin')->default(false);
            $table->boolean('is_required_for_client')->default(false);

            $table->unsignedTinyInteger('max_select')->nullable();

            $table->string('price_type')->default(MenuItemGroupPriceType::INCREASE->value);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique('name');
            $table->unique('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_menu_item_groups');
    }
};

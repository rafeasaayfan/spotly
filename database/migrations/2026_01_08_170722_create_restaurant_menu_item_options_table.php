<?php

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
        Schema::create('restaurant_menu_item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('restaurant_menu_items')->onDelete('cascade');
            $table->foreignId('option_group_id')->constrained('restaurant_menu_item_groups')->onDelete('cascade');

            $table->string('name');
            $table->string('name_ar');

            $table->decimal('price_delta', 10, 2)->default(0);
            $table->boolean('is_increase')->default(true);
            
            $table->string('option_explain')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['menu_item_id', 'option_group_id', 'name'], 'rmio_item_group_name');
            $table->unique(['menu_item_id', 'option_group_id', 'name_ar'], 'rmio_item_group_name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_menu_item_options');
    }
};

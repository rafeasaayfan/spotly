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
        Schema::create('restaurant_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('restaurant_orders')->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained('restaurant_menu_items')->onDelete('set null');
            $table->foreignId('option_id')->nullable()->constrained('restaurant_menu_item_options')->onDelete('set null');

            $table->json('image_urls')->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->json('options')->nullable();
            
            $table->timestamps();

            $table->unique(
                ['order_id', 'menu_item_id', 'option_id'],
                'order_item_unique_idx'
            );
            $table->index(['order_id', 'option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_order_items');
    }
};

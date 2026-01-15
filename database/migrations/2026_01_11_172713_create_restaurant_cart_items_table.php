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
        Schema::create('restaurant_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('restaurant_carts')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained('restaurant_menu_items')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('restaurant_menu_item_options')->onDelete('cascade');

            $table->json('image_urls')->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->json('options')->nullable();
            $table->string('options_hash')->nullable();
            
            $table->timestamps();

            $table->unique(
                ['cart_id', 'menu_item_id', 'option_id', 'options_hash'],
                'ci_unique_options_hash_idx'
            );
            $table->index(['cart_id', 'option_id', 'options_hash'], 'ci_options_hash_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_cart_items');
    }
};

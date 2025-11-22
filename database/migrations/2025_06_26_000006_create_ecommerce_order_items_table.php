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
        Schema::create('ecommerce_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ecommerce_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('ecommerce_products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('ecommerce_product_variants')->onDelete('cascade');

            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null');
            $table->json('image_urls')->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->json('attributes')->nullable();
            
            $table->timestamps();

            $table->unique(
                ['order_id', 'product_id', 'product_variant_id'],
                'order_item_unique_idx'
            );
            $table->index(['order_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_order_items');
    }
};

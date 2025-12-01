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
        Schema::create('ecommerce_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('ecommerce_carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('ecommerce_products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('ecommerce_product_variants')->onDelete('cascade');

            $table->json('image_urls')->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->json('attributes')->nullable();
            $table->string('attributes_hash')->nullable();
            
            $table->timestamps();

            $table->unique(
                ['cart_id', 'product_id', 'product_variant_id', 'attributes_hash'],
                'ci_unique_attributes_hash_idx'
            );
            $table->index(['cart_id', 'product_variant_id', 'attributes_hash'], 'ci_attributes_hash_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_cart_items');
    }
};

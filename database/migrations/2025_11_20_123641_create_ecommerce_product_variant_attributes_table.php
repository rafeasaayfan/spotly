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
        Schema::create('ecommerce_product_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('ecommerce_product_variants')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('ecommerce_product_attributes')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->nullable()->constrained('ecommerce_product_attribute_values')->onDelete('cascade');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_variant_id', 'attribute_id'], 'epa_unique_idx');
            $table->index(['product_variant_id', 'attribute_id'], 'epa_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_product_variant_attributes');
    }
};

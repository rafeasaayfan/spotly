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
            $table->foreignId('attribute_value_id')->constrained('ecommerce_product_attribute_values')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_variant_id', 'attribute_value_id'], 'epva_unique_idx');
            $table->index(['product_variant_id', 'attribute_value_id'], 'epva_idx');
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

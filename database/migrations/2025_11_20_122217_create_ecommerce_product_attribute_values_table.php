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
        Schema::create('ecommerce_product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('ecommerce_product_attributes')->onDelete('cascade');

            $table->string('value');
            $table->string('value_ar')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['attribute_id', 'value'], 'epav_unique_idx');
            $table->unique(['attribute_id', 'value_ar'], 'epav_ar_unique_idx');
            $table->index(['attribute_id', 'value'], 'epav_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_product_attribute_values');
    }
};

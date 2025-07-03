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
        Schema::create('ecommerce_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);

            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();

            $table->string('dimension_unit')->default('cm');
            $table->string('weight_unit')->default('kg');

            $table->json('colors')->nullable();

            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);

            $table->boolean('is_in_home')->default(false);
            $table->boolean('is_special')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_downloadable')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['website_id', 'is_active']);
            $table->index(['category_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_products');
    }
};

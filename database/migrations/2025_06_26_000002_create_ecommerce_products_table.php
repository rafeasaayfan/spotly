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
            $table->string('slug');

            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();

            $table->text('short_description')->nullable();
            $table->text('description')->nullable();

            $table->integer('views_count')->default(0);

            $table->boolean('is_in_home')->default(false);
            $table->boolean('is_special')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'slug']);
            $table->index(['website_id', 'slug']);
            $table->index(['website_id', 'is_active']);
            $table->index(['website_id', 'is_special']);
            $table->index(['website_id', 'is_in_home']);
            $table->index(['category_id', 'is_active']);
            $table->index(['category_id', 'is_special']);
            $table->index(['category_id', 'is_in_home']);
            $table->index(['brand_id', 'is_active']);
            $table->index(['brand_id', 'is_special']);
            $table->index(['brand_id', 'is_in_home']);
            $table->index('views_count');
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

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
        Schema::create('restaurant_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            $table->string('name');
            $table->string('slug');

            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();

            $table->text('short_description')->nullable();
            $table->text('description')->nullable();

            $table->integer('views_count')->default(0);

            $table->boolean('is_discount')->default(false);
            $table->boolean('is_in_home')->default(false);
            $table->boolean('is_special')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();

            $table->unique(['website_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_menu_items');
    }
};

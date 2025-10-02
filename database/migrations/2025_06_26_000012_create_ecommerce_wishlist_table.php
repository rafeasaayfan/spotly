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
        Schema::create('ecommerce_wishlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('website_user_id')->constrained('website_users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('ecommerce_products')->onDelete('cascade');

            $table->string('color')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['website_user_id', 'product_id', 'website_id']);
            $table->index(['website_user_id', 'website_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_wishlist');
    }
};

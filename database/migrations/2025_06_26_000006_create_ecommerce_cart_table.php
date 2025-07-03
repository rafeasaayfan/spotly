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
        Schema::create('ecommerce_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('website_user_id')->constrained('website_users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('ecommerce_products')->onDelete('cascade');

            $table->string('session_id')->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            $table->string('color')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['website_id', 'website_user_id', 'product_id', 'session_id'],
                'cart_unique_idx'
            );
            $table->index(['website_user_id', 'website_id']);
            $table->index(['session_id', 'website_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_cart');
    }
};

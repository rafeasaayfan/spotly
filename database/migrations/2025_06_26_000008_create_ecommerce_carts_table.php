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
        Schema::create('ecommerce_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('website_user_id')->nullable()->constrained('website_users')->onDelete('cascade');

            $table->string('session_id')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->enum('status', ['pending', 'checked_out', 'abandoned'])->default('pending');
            $table->timestamps();

            $table->unique(['website_id', 'website_user_id'], 'cart_unique_user_idx');
            $table->unique(['website_id', 'session_id'], 'cart_unique_session_idx');

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

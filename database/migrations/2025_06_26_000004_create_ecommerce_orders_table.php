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
        Schema::create('ecommerce_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('website_user_id')->constrained('website_users')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');

            $table->string('order_number')->unique();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->string('delivery_address');
            $table->string('city');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->enum('status', [
                'pending',
                'confirmed',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['website_id', 'status']);
            $table->index(['website_user_id', 'status']);
            $table->index('order_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_orders');
    }
};

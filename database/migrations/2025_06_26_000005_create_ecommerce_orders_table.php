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
            $table->foreignId('website_user_id')->nullable()->constrained('website_users')->onDelete('set null');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->foreignId('delivery_fee_id')->nullable()->constrained('delivery_fees')->onDelete('set null');

            $table->string('session_id')->nullable();

            $table->string('order_number')->unique();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('total_amount', 12, 2);

            $table->string('phone_number');
            $table->string('city');
            $table->string('delivery_address');

            $table->text('note')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamp('status_changed_at')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'delivered',
                'rejected',
                'cancelled',
                'refunded',
            ])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'order_number']);
            $table->index(['website_id', 'status']);
            $table->index(['website_user_id', 'status']);
            $table->index('order_number');
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

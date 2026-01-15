<?php

use App\Enums\Websites\Restaurant\OrderStatus;
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
        Schema::create('restaurant_track_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('restaurant_orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('website_users')->onDelete('set null');

            $table->text('status_reason')->nullable();
            $table->string('status')->default(OrderStatus::PENDING->value);

            $table->boolean('is_session_order')->default(false);

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_track_orders');
    }
};

<?php

use App\Enums\Spotly\PlanCurrency;
use App\Enums\Spotly\PlanDuration;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_type_id')->nullable()->constrained('website_types')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('currency')->default(PlanCurrency::USD->value);
            $table->string('duration')->default(PlanDuration::FREE_THREE_DAYS->value);
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['website_type_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};

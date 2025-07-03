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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('website_type_id')->nullable()->constrained('website_types')->onDelete('set null');
            $table->foreignId('approved_or_denied_by')->nullable()->constrained('users')->onDelete('set null');

            $table->string('name')->unique();
            $table->string('subdomain')->unique();
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique()->nullable();

            $table->text('about_us')->nullable();

            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();

            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();

            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');

            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'denied', 'approved'])->default('pending');

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};

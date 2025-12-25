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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');

            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('type');

            $table->text('description')->nullable();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();

            $table->unique(['website_id', 'name']);
            $table->unique(['website_id', 'name_ar']);
            $table->index(['website_id', 'name']);
            $table->index(['website_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};

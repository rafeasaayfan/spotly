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
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');

            $table->string('value');
            $table->string('value_ar')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['attribute_id', 'value'], 'av_unique_idx');
            $table->unique(['attribute_id', 'value_ar'], 'av_ar_unique_idx');
            $table->index(['attribute_id', 'value'], 'av_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};

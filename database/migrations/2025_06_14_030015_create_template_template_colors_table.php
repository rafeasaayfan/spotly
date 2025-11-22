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
        Schema::create('template_template_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_type_id')->constrained('website_types')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->foreignId('template_color_id')->constrained('template_colors')->onDelete('cascade');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['website_type_id', 'template_id', 'template_color_id'], 'ttc_unique_idx');
            $table->index(['website_type_id', 'template_id', 'template_color_id'], 'ttc_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_template_colors');
    }
};

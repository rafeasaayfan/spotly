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
        Schema::create('color_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_color_id')->constrained('website_colors')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('key');
            $table->text('description');
            $table->string('light');
            $table->string('dark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_templates');
    }
};

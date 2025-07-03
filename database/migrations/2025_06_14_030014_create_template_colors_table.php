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
        Schema::create('template_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title')->unique();

            $table->string('bg_body_light')->nullable();
            $table->string('bg_body_dark')->nullable();
            $table->string('bg_nav_light')->nullable();
            $table->string('bg_nav_dark')->nullable();
            $table->string('bg_footer_light')->nullable();
            $table->string('bg_footer_dark')->nullable();

            $table->string('bg_field_light')->nullable();
            $table->string('bg_field_dark')->nullable();

            $table->string('bg_card_light')->nullable();
            $table->string('bg_card_dark')->nullable();


            $table->string('forground_light')->nullable();
            $table->string('forground_dark')->nullable();
            $table->string('forground_active_light')->nullable();
            $table->string('forground_active_dark')->nullable();
            $table->string('forground_muted_light')->nullable();
            $table->string('forground_muted_dark')->nullable();

            $table->string('primary_light')->nullable();
            $table->string('primary_dark')->nullable();
            $table->string('danger_light')->nullable();
            $table->string('danger_dark')->nullable();
            $table->string('secondary_light')->nullable();
            $table->string('secondary_dark')->nullable();

            $table->string('border_color_light')->nullable();
            $table->string('border_color_dark')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_custom')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_colors');
    }
};

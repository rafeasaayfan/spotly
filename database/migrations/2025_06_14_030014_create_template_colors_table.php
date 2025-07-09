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
            $table->string('name')->unique();

            $table->string('bg_body_light');
            $table->string('bg_body_dark');
            $table->string('bg_nav_light');
            $table->string('bg_nav_dark');
            $table->string('bg_footer_light');
            $table->string('bg_footer_dark');

            $table->string('bg_field_light');
            $table->string('bg_field_dark');

            $table->string('bg_card_light');
            $table->string('bg_card_dark');

            $table->string('forground_light');
            $table->string('forground_dark');
            $table->string('forground_active_light');
            $table->string('forground_active_dark');
            $table->string('forground_muted_light');
            $table->string('forground_muted_dark');

            $table->string('primary_light');
            $table->string('primary_dark');
            $table->string('danger_light');
            $table->string('danger_dark');
            $table->string('secondary_light');
            $table->string('secondary_dark');

            $table->string('border_color_light');
            $table->string('border_color_dark');

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

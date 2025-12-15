<?php

namespace Database\Seeders\Tables;

use App\Models\TemplateColor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'spotly-ui-colors',

            'bg_body_light' => '#FFFFFF',
            'bg_body_dark' => 'oklch(13% 0.028 261.692)',
            'bg_nav_light' => 'rgba(0, 0, 0, 0.03)',
            'bg_nav_dark' => 'rgba(255, 255, 255, 0.03)',
            'bg_footer_light' => 'rgba(0, 0, 0, 0.03)',
            'bg_footer_dark' => 'rgba(255, 255, 255, 0.03)',

            'foreground_light' => 'hsl(0 0% 16%)',
            'foreground_dark' => 'hsl(0 0% 78%)',
            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#FFFFFF',
            'foreground_muted_light' => 'oklch(55.1% 0.027 264.364)',
            'foreground_muted_dark' => 'oklch(55.1% 0.027 264.364)',

            'bg_field_light' => 'hsl(218, 92%, 95%)',
            'bg_field_dark' => 'oklch(18% 0.034 264.665)',

            'bg_card_light' => 'rgba(0, 0, 0, 0.01)',
            'bg_card_hover_light' => 'rgba(0, 0, 0, 0.03)',
            'bg_card_dark' => 'rgba(255, 255, 255, 0.01)',
            'bg_card_hover_dark' => 'rgba(255, 255, 255, 0.03)',

            'bg_content_light' => 'rgba(37, 99, 235, 0.1)',
            'bg_content_hover_light' => 'rgba(37, 99, 235, 0.2)',
            'bg_content_active_light' => 'rgba(37, 99, 235, 0.25)',
            'bg_content_dark' => 'rgba(37, 99, 235, 0.07)',
            'bg_content_hover_dark' => 'rgba(37, 99, 235, 0.15)',
            'bg_content_active_dark' => 'rgba(37, 99, 235, 0.2)',

            'bg_dropdown_light' => '#FFFFFF',
            'bg_dropdown_dark' => '#000000',

            'primary_light' => 'hsl(221 80% 40%)',
            'primary_hover_light' => 'hsl(221 80% 30%)',
            'primary_dark' => 'hsl(221, 83%, 45%)',
            'primary_hover_dark' => 'hsl(221 83% 55%)',
            'danger_light' => 'hsl(0 80% 50%)',
            'danger_hover_light' => 'hsl(0 80% 40%)',
            'danger_dark' => 'hsl(0, 74%, 45%)',
            'danger_hover_dark' => 'hsl(0, 74%, 55%)',
            'secondary_light' => '#666666',
            'secondary_hover_light' => '#595959',
            'secondary_dark' => '#4d4d4d',
            'secondary_hover_dark' => '#404040',

            'text_for_primary_light' => '#FFFFFF',
            'text_for_primary_dark' => '#FFFFFF',
            'text_for_danger_light' => '#FFFFFF',
            'text_for_danger_dark' => '#FFFFFF',

            'border_color_light' => 'hsl(0 0% 88%)',
            'border_color_dark' => 'oklch(21% 0.034 264.665)',

            'description' => 'Our spotly colors',

            'is_custom' => '0',
            'is_active' => '1',
        ]);

        // Modern Purple/Violet Theme
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'basic-ui-colors',

            'bg_body_light' => '#FAFAFA',
            'bg_body_dark' => 'hsl(220 15% 10%)',

            'bg_nav_light' => '#FFFFFF',
            'bg_nav_dark' => 'hsl(220 15% 12%)',

            'bg_footer_light' => '#FFFFFF',
            'bg_footer_dark' => 'hsl(220 15% 12%)',

            'foreground_light' => 'hsl(220 20% 20%)',
            'foreground_dark' => 'hsl(0 0% 90%)',

            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#FFFFFF',

            'foreground_muted_light' => 'hsl(220 10% 50%)',
            'foreground_muted_dark' => 'hsl(220 10% 65%)',

            'bg_field_light' => 'hsl(210 60% 97%)',
            'bg_field_dark' => 'hsl(220 18% 18%)',

            'bg_card_light' => '#FFFFFF',
            'bg_card_hover_light' => 'hsl(0 0% 96%)',
            'bg_card_dark' => 'hsl(220 15% 13%)',
            'bg_card_hover_dark' => 'hsl(220 15% 18%)',

            'bg_content_light' => 'hsl(210 90% 92%)',
            'bg_content_hover_light' => 'hsl(210 90% 88%)',
            'bg_content_active_light' => 'hsl(210 90% 83%)',

            'bg_content_dark' => 'hsl(210 40% 22%)',
            'bg_content_hover_dark' => 'hsl(210 40% 28%)',
            'bg_content_active_dark' => 'hsl(210 40% 34%)',

            'bg_dropdown_light' => '#FFFFFF',
            'bg_dropdown_dark' => 'hsl(220 15% 15%)',

            'primary_light' => 'hsl(215 85% 45%)',
            'primary_hover_light' => 'hsl(215 85% 35%)',
            'primary_dark' => 'hsl(215 80% 50%)',
            'primary_hover_dark' => 'hsl(215 80% 60%)',

            'danger_light' => 'hsl(0 75% 55%)',
            'danger_hover_light' => 'hsl(0 75% 45%)',
            'danger_dark' => 'hsl(0 70% 60%)',
            'danger_hover_dark' => 'hsl(0 70% 70%)',

            'secondary_light' => 'hsl(220 10% 45%)',
            'secondary_hover_light' => 'hsl(220 10% 40%)',
            'secondary_dark' => 'hsl(220 10% 60%)',
            'secondary_hover_dark' => 'hsl(220 10% 65%)',

            'text_for_primary_light' => '#FFFFFF',
            'text_for_primary_dark' => '#FFFFFF',
            'text_for_danger_light' => '#FFFFFF',
            'text_for_danger_dark' => '#FFFFFF',

            'border_color_light' => 'hsl(220 15% 85%)',
            'border_color_dark' => 'hsl(220 15% 22%)',

            'description' => 'Basic clean UI colors',

            'is_custom' => '0',
            'is_active' => '1',
        ]);

        // 
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'special-premium-colors',

            'bg_body_light' => '#FDFBFF',
            'bg_body_dark' => 'hsl(260 30% 8%)',

            'bg_nav_light' => '#FFFFFF',
            'bg_nav_dark' => 'hsl(260 30% 11%)',

            'bg_footer_light' => '#FFFFFF',
            'bg_footer_dark' => 'hsl(260 30% 11%)',

            'foreground_light' => 'hsl(260 20% 20%)',
            'foreground_dark' => 'hsl(0 0% 92%)',

            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#FFFFFF',

            'foreground_muted_light' => 'hsl(260 10% 48%)',
            'foreground_muted_dark' => 'hsl(260 10% 65%)',

            'bg_field_light' => 'hsl(280 80% 96%)',
            'bg_field_dark' => 'hsl(260 25% 16%)',

            'bg_card_light' => '#FFFFFF',
            'bg_card_hover_light' => 'hsl(260 20% 96%)',
            'bg_card_dark' => 'hsl(260 28% 14%)',
            'bg_card_hover_dark' => 'hsl(260 28% 20%)',

            'bg_content_light' => 'hsl(190 85% 85%)',
            'bg_content_hover_light' => 'hsl(190 85% 80%)',
            'bg_content_active_light' => 'hsl(190 85% 75%)',

            'bg_content_dark' => 'hsl(190 35% 24%)',
            'bg_content_hover_dark' => 'hsl(190 35% 30%)',
            'bg_content_active_dark' => 'hsl(190 35% 36%)',

            'bg_dropdown_light' => '#FFFFFF',
            'bg_dropdown_dark' => 'hsl(260 30% 14%)',

            'primary_light' => 'hsl(275 75% 55%)',      // Purple
            'primary_hover_light' => 'hsl(275 75% 45%)',
            'primary_dark' => 'hsl(275 70% 60%)',
            'primary_hover_dark' => 'hsl(275 70% 70%)',

            'danger_light' => 'hsl(350 75% 55%)',
            'danger_hover_light' => 'hsl(350 75% 45%)',
            'danger_dark' => 'hsl(350 70% 60%)',
            'danger_hover_dark' => 'hsl(350 70% 70%)',

            'secondary_light' => 'hsl(45 85% 45%)',     // Gold
            'secondary_hover_light' => 'hsl(45 85% 40%)',
            'secondary_dark' => 'hsl(45 85% 55%)',
            'secondary_hover_dark' => 'hsl(45 85% 65%)',

            'text_for_primary_light' => '#FFFFFF',
            'text_for_primary_dark' => '#FFFFFF',
            'text_for_danger_light' => '#FFFFFF',
            'text_for_danger_dark' => '#FFFFFF',

            'border_color_light' => 'hsl(260 15% 86%)',
            'border_color_dark' => 'hsl(260 15% 22%)',

            'description' => 'Special premium vibrant UI colors',

            'is_custom' => '0',
            'is_active' => '1',
        ]);
    }
}

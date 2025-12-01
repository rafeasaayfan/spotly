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
    }
}

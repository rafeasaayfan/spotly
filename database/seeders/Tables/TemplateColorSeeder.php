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
        // Spotly Template Colors
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

        // Basic Template Colors
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'basic-ui-colors',

            'bg_body_light' => '#ffffff',
            'bg_body_dark' => '#1a1a1a',
            'bg_nav_light' => '#f8f9fa',
            'bg_nav_dark' => '#2d2d2d',
            'bg_footer_light' => '#f8f9fa',
            'bg_footer_dark' => '#2d2d2d',

            'foreground_light' => '#212529',
            'foreground_dark' => '#e9ecef',
            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#ffffff',
            'foreground_muted_light' => '#6c757d',
            'foreground_muted_dark' => '#adb5bd',

            'bg_field_light' => '#ffffff',
            'bg_field_dark' => '#343a40',

            'bg_card_light' => '#ffffff',
            'bg_card_hover_light' => '#f8f9fa',
            'bg_card_dark' => '#2d2d2d',
            'bg_card_hover_dark' => '#3a3a3a',

            'bg_content_light' => '#ffffff',
            'bg_content_hover_light' => '#f8f9fa',
            'bg_content_active_light' => '#e9ecef',
            'bg_content_dark' => '#2d2d2d',
            'bg_content_hover_dark' => '#3a3a3a',
            'bg_content_active_dark' => '#495057',

            'bg_dropdown_light' => '#ffffff',
            'bg_dropdown_dark' => '#343a40',

            'primary_light' => '#0d6efd',
            'primary_hover_light' => '#0b5ed7',
            'primary_dark' => '#0d6efd',
            'primary_hover_dark' => '#3d8bfd',
            'danger_light' => '#dc3545',
            'danger_hover_light' => '#bb2d3b',
            'danger_dark' => '#dc3545',
            'danger_hover_dark' => '#e35d6a',
            'secondary_light' => '#6c757d',
            'secondary_hover_light' => '#5c636a',
            'secondary_dark' => '#6c757d',
            'secondary_hover_dark' => '#8a9199',

            'text_for_primary_light' => '#ffffff',
            'text_for_primary_dark' => '#ffffff',
            'text_for_danger_light' => '#ffffff',
            'text_for_danger_dark' => '#ffffff',

            'border_color_light' => '#dee2e6',
            'border_color_dark' => '#495057',

            'description' => 'A clean and simple color scheme perfect for any project',
            'is_custom' => false,
            'is_active' => true,
        ]);

        // Modern Template Colors
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'modern-ui-colors',

            'bg_body_light' => '#f5f7fa',
            'bg_body_dark' => '#0f1419',
            'bg_nav_light' => '#ffffff',
            'bg_nav_dark' => '#1a1f2e',
            'bg_footer_light' => '#2c3e50',
            'bg_footer_dark' => '#1a1f2e',

            'foreground_light' => '#2d3748',
            'foreground_dark' => '#e2e8f0',
            'foreground_active_light' => '#1a202c',
            'foreground_active_dark' => '#f7fafc',
            'foreground_muted_light' => '#718096',
            'foreground_muted_dark' => '#a0aec0',

            'bg_field_light' => '#ffffff',
            'bg_field_dark' => '#1e2530',

            'bg_card_light' => '#ffffff',
            'bg_card_hover_light' => '#f7fafc',
            'bg_card_dark' => '#1e2530',
            'bg_card_hover_dark' => '#2a3441',

            'bg_content_light' => '#ffffff',
            'bg_content_hover_light' => '#edf2f7',
            'bg_content_active_light' => '#e2e8f0',
            'bg_content_dark' => '#1e2530',
            'bg_content_hover_dark' => '#2a3441',
            'bg_content_active_dark' => '#374151',

            'bg_dropdown_light' => '#ffffff',
            'bg_dropdown_dark' => '#1e2530',

            'primary_light' => '#667eea',
            'primary_hover_light' => '#5568d3',
            'primary_dark' => '#7c3aed',
            'primary_hover_dark' => '#9333ea',
            'danger_light' => '#f56565',
            'danger_hover_light' => '#e53e3e',
            'danger_dark' => '#fc8181',
            'danger_hover_dark' => '#f56565',
            'secondary_light' => '#48bb78',
            'secondary_hover_light' => '#38a169',
            'secondary_dark' => '#48bb78',
            'secondary_hover_dark' => '#68d391',

            'text_for_primary_light' => '#ffffff',
            'text_for_primary_dark' => '#ffffff',
            'text_for_danger_light' => '#ffffff',
            'text_for_danger_dark' => '#ffffff',

            'border_color_light' => '#e2e8f0',
            'border_color_dark' => '#374151',

            'description' => 'A modern and vibrant color palette with bold accents',
            'is_custom' => false,
            'is_active' => false,
        ]);

        // Ecommerce Template Colors
        TemplateColor::create([
            'created_by' => 1,
            'name' => 'ecommerce-ui-colors',

            'bg_body_light' => '#fafafa',
            'bg_body_dark' => '#121212',
            'bg_nav_light' => '#ffffff',
            'bg_nav_dark' => '#1e1e1e',
            'bg_footer_light' => '#263238',
            'bg_footer_dark' => '#1e1e1e',

            'foreground_light' => '#263238',
            'foreground_dark' => '#e0e0e0',
            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#ffffff',
            'foreground_muted_light' => '#757575',
            'foreground_muted_dark' => '#9e9e9e',

            'bg_field_light' => '#ffffff',
            'bg_field_dark' => '#2c2c2c',

            'bg_card_light' => '#ffffff',
            'bg_card_hover_light' => '#f5f5f5',
            'bg_card_dark' => '#1e1e1e',
            'bg_card_hover_dark' => '#2c2c2c',

            'bg_content_light' => '#ffffff',
            'bg_content_hover_light' => '#fafafa',
            'bg_content_active_light' => '#f5f5f5',
            'bg_content_dark' => '#1e1e1e',
            'bg_content_hover_dark' => '#2c2c2c',
            'bg_content_active_dark' => '#383838',

            'bg_dropdown_light' => '#ffffff',
            'bg_dropdown_dark' => '#2c2c2c',

            'primary_light' => '#ff6b35',
            'primary_hover_light' => '#ff5722',
            'primary_dark' => '#ff7849',
            'primary_hover_dark' => '#ff8a65',
            'danger_light' => '#d32f2f',
            'danger_hover_light' => '#c62828',
            'danger_dark' => '#ef5350',
            'danger_hover_dark' => '#e57373',
            'secondary_light' => '#00897b',
            'secondary_hover_light' => '#00796b',
            'secondary_dark' => '#26a69a',
            'secondary_hover_dark' => '#4db6ac',

            'text_for_primary_light' => '#ffffff',
            'text_for_primary_dark' => '#ffffff',
            'text_for_danger_light' => '#ffffff',
            'text_for_danger_dark' => '#ffffff',

            'border_color_light' => '#e0e0e0',
            'border_color_dark' => '#383838',

            'description' => 'Optimized for e-commerce with high contrast and conversion-focused colors',
            'is_custom' => false,
            'is_active' => false,
        ]);
    }
}

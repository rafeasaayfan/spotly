<?php

namespace Database\Seeders\Tables;

use App\Models\TemplateColor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateColorsSeeder extends Seeder
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
            'bg_body_dark' => '#000000',
            'bg_nav_light' => 'rgba(0,0,0,0.03)',
            'bg_nav_dark' => 'rgba(255, 255, 255, 0.03)',
            'bg_footer_light' => 'rgba(0,0,0,0.03)',
            'bg_footer_dark' => 'rgba(255, 255, 255, 0.03)',
    
            'foreground_light' => '#292929',
            'foreground_dark' => '#cccccc',
            'foreground_active_light' => '#000000',
            'foreground_active_dark' => '#FFFFFF',
            'foreground_muted_light' => '#404040',
            'foreground_muted_dark' => '#808080',
    
            'bg_field_light' => '#cad3e8',
            'bg_field_dark' => '#111827',
    
            'bg_card_light' => 'rgba(0,0,0,0.05)',
            'bg_card_hover_light' => 'rgba(0,0,0,0.08)',
            'bg_card_dark' => 'rgba(255,255,255,0.05)',
            'bg_card_hover_dark' => 'rgba(255,255,255,0.08)',
    
            'bg_content_light' => '#eaeaec',
            'bg_content_hover_light' => '#18181bcc',
            'bg_content_active_light' => '#cfcfd3cc',
            'bg_content_dark' => '#111113cc',
            'bg_content_hover_dark' => '#18181bcc',
            'bg_content_active_dark' => '#242428cc',
    
            'bg_dropdown_light' => '#FFFFFF',
            'bg_dropdown_dark' => '#000000',
    
            'primary_light' => '#0f408a',
            'primary_hover_light' => '#0d3673',
            'primary_dark' => '#1659e9',
            'primary_hover_dark' => '#1450d2',
            'danger_light' => '#b81414',
            'danger_hover_light' => '#a11212',
            'danger_dark' => '#b11b1b',
            'danger_hover_dark' => '#a91919',
            'secondary_light' => '#666666',
            'secondary_hover_light' => '#595959',
            'secondary_dark' => '#4d4d4d',
            'secondary_hover_dark' => '#404040',
    
            'border_color_light' => '#e0e0e0',
            'border_color_dark' => '#0d0d0d',
    
            'description' => 'Our spotly colors',
    
            'is_custom' => '0',
            'is_active' => '1',
        ]);
    }
}

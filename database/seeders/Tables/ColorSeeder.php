<?php

namespace Database\Seeders\Tables;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $colors = [
            ['name' => 'Black', 'ar_name' => 'أسود', 'code' => '#000000'],
            ['name' => 'White', 'ar_name' => 'أبيض', 'code' => '#FFFFFF'],
            ['name' => 'Red', 'ar_name' => 'أحمر', 'code' => '#FF0000'],
            ['name' => 'Blue', 'ar_name' => 'أزرق', 'code' => '#0000FF'],
            ['name' => 'Green', 'ar_name' => 'أخضر', 'code' => '#008000'],
            ['name' => 'Yellow', 'ar_name' => 'أصفر', 'code' => '#FFFF00'],
            ['name' => 'Orange', 'ar_name' => 'برتقالي', 'code' => '#FFA500'],
            ['name' => 'Purple', 'ar_name' => 'بنفسجي', 'code' => '#800080'],
            ['name' => 'Pink', 'ar_name' => 'وردي', 'code' => '#FFC0CB'],
            ['name' => 'Brown', 'ar_name' => 'بني', 'code' => '#A52A2A'],
            ['name' => 'Gray', 'ar_name' => 'رمادي', 'code' => '#808080'],
            ['name' => 'Navy Blue', 'ar_name' => 'كحلي', 'code' => '#000080'],
            ['name' => 'Beige', 'ar_name' => 'بيج', 'code' => '#F5F5DC'],
            ['name' => 'Maroon', 'ar_name' => 'كستنائي', 'code' => '#800000'],
            ['name' => 'Olive', 'ar_name' => 'زيتوني', 'code' => '#808000'],
            ['name' => 'Teal', 'ar_name' => 'تركوازي', 'code' => '#008080'],
            ['name' => 'Cyan', 'ar_name' => 'سماوي', 'code' => '#00FFFF'],
            ['name' => 'Magenta', 'ar_name' => 'أرجواني', 'code' => '#FF00FF'],
            ['name' => 'Lime', 'ar_name' => 'ليموني', 'code' => '#00FF00'],
            ['name' => 'Silver', 'ar_name' => 'فضي', 'code' => '#C0C0C0'],
            ['name' => 'Gold', 'ar_name' => 'ذهبي', 'code' => '#FFD700'],
            ['name' => 'Coral', 'ar_name' => 'مرجاني', 'code' => '#FF7F50'],
            ['name' => 'Salmon', 'ar_name' => 'سلموني', 'code' => '#FA8072'],
            ['name' => 'Turquoise', 'ar_name' => 'فيروزي', 'code' => '#40E0D0'],
            ['name' => 'Lavender', 'ar_name' => 'لافندر', 'code' => '#E6E6FA'],
            ['name' => 'Mint', 'ar_name' => 'منثولي', 'code' => '#98FB98'],
            ['name' => 'Peach', 'ar_name' => 'خوخي', 'code' => '#FFE5B4'],
            ['name' => 'Ivory', 'ar_name' => 'عاجي', 'code' => '#FFFFF0'],
            ['name' => 'Khaki', 'ar_name' => 'كاكي', 'code' => '#F0E68C'],
            ['name' => 'Burgundy', 'ar_name' => 'بورجوندي', 'code' => '#800020'],
            ['name' => 'Indigo', 'ar_name' => 'نيلي', 'code' => '#4B0082'],
            ['name' => 'Violet', 'ar_name' => 'بنفسجي فاتح', 'code' => '#8A2BE2'],
            ['name' => 'Cream', 'ar_name' => 'كريمي', 'code' => '#FFFDD0'],
            ['name' => 'Charcoal', 'ar_name' => 'فحمي', 'code' => '#36454F'],
            ['name' => 'Tan', 'ar_name' => 'أسمر', 'code' => '#D2B48C'],
            ['name' => 'Rose', 'ar_name' => 'وردي فاتح', 'code' => '#FF007F'],
            ['name' => 'Sky Blue', 'ar_name' => 'أزرق سماوي', 'code' => '#87CEEB'],
            ['name' => 'Emerald', 'ar_name' => 'زمردي', 'code' => '#50C878'],
            ['name' => 'Amber', 'ar_name' => 'كهرماني', 'code' => '#FFBF00'],
            ['name' => 'Ruby', 'ar_name' => 'ياقوتي', 'code' => '#E0115F'],
            ['name' => 'Sapphire', 'ar_name' => 'ياقوت أزرق', 'code' => '#0F52BA'],
            ['name' => 'Plum', 'ar_name' => 'برقوقي', 'code' => '#8E4585'],
            ['name' => 'Mauve', 'ar_name' => 'موف', 'code' => '#E0B0FF'],
            ['name' => 'Crimson', 'ar_name' => 'قرمزي', 'code' => '#DC143C'],
            ['name' => 'Forest Green', 'ar_name' => 'أخضر غابي', 'code' => '#228B22'],
            ['name' => 'Royal Blue', 'ar_name' => 'أزرق ملكي', 'code' => '#4169E1'],
            ['name' => 'Champagne', 'ar_name' => 'شامبانيا', 'code' => '#F7E7CE'],
            ['name' => 'Copper', 'ar_name' => 'نحاسي', 'code' => '#B87333'],
            ['name' => 'Bronze', 'ar_name' => 'برونزي', 'code' => '#CD7F32'],
            ['name' => 'Aqua', 'ar_name' => 'أكواز', 'code' => '#00CED1'],
            ['name' => 'Azure', 'ar_name' => 'لازوردي', 'code' => '#007FFF'],
            ['name' => 'Bisque', 'ar_name' => 'بسكوي', 'code' => '#FFE4C4'],
            ['name' => 'Blush', 'ar_name' => 'وردي خفيف', 'code' => '#DE5D83'],
            ['name' => 'Brick Red', 'ar_name' => 'أحمر طوبي', 'code' => '#CB4154'],
            ['name' => 'Burnt Orange', 'ar_name' => 'برتقالي محروق', 'code' => '#CC5500'],
            ['name' => 'Cadet Blue', 'ar_name' => 'أزرق كاديت', 'code' => '#5F9EA0'],
            ['name' => 'Caramel', 'ar_name' => 'كراميل', 'code' => '#AF6E4D'],
            ['name' => 'Cerulean', 'ar_name' => 'سماوي عميق', 'code' => '#007BA7'],
            ['name' => 'Chocolate', 'ar_name' => 'شوكولاتي', 'code' => '#7B3F00'],
            ['name' => 'Cobalt Blue', 'ar_name' => 'أزرق كوبالت', 'code' => '#0047AB'],
            ['name' => 'Coffee', 'ar_name' => 'قهوي', 'code' => '#6F4E37'],
            ['name' => 'Crimson Red', 'ar_name' => 'أحمر قرمزي', 'code' => '#990000'],
            ['name' => 'Dark Blue', 'ar_name' => 'أزرق داكن', 'code' => '#00008B'],
            ['name' => 'Dark Green', 'ar_name' => 'أخضر داكن', 'code' => '#006400'],
            ['name' => 'Dark Red', 'ar_name' => 'أحمر داكن', 'code' => '#8B0000'],
            ['name' => 'Denim', 'ar_name' => 'دنيم', 'code' => '#1560BD'],
            ['name' => 'Dusty Rose', 'ar_name' => 'وردي ترابي', 'code' => '#C9A9A6'],
            ['name' => 'Eggplant', 'ar_name' => 'باذنجاني', 'code' => '#614051'],
            ['name' => 'Fern Green', 'ar_name' => 'أخضر سرخس', 'code' => '#4F7942'],
            ['name' => 'Fuchsia', 'ar_name' => 'فوشيا', 'code' => '#FF1493'],
            ['name' => 'Garnet', 'ar_name' => 'عقيق', 'code' => '#733635'],
            ['name' => 'Ginger', 'ar_name' => 'زنجبيلي', 'code' => '#B06500'],
            ['name' => 'Grape', 'ar_name' => 'عنب', 'code' => '#6F2DA8'],
            ['name' => 'Honey', 'ar_name' => 'عسلي', 'code' => '#FFC30B'],
            ['name' => 'Hot Pink', 'ar_name' => 'وردي ساخن', 'code' => '#FF69B4'],
            ['name' => 'Jade', 'ar_name' => 'يشب', 'code' => '#00A86B'],
            ['name' => 'Jet Black', 'ar_name' => 'أسود فاحم', 'code' => '#0A0A0A'],
            ['name' => 'Lemon', 'ar_name' => 'ليمون', 'code' => '#FFF700'],
            ['name' => 'Light Blue', 'ar_name' => 'أزرق فاتح', 'code' => '#ADD8E6'],
            ['name' => 'Light Green', 'ar_name' => 'أخضر فاتح', 'code' => '#90EE90'],
            ['name' => 'Light Pink', 'ar_name' => 'وردي باهت', 'code' => '#FFB6C1'],
            ['name' => 'Lilac', 'ar_name' => 'أرجواني فاتح', 'code' => '#C8A2C8'],
            ['name' => 'Mahogany', 'ar_name' => 'ماهوجني', 'code' => '#C04000'],
            ['name' => 'Mango', 'ar_name' => 'مانجو', 'code' => '#FFC324'],
            ['name' => 'Midnight Blue', 'ar_name' => 'أزرق منتصف الليل', 'code' => '#191970'],
            ['name' => 'Moss Green', 'ar_name' => 'أخضر طحلبي', 'code' => '#8A9A5B'],
            ['name' => 'Mustard', 'ar_name' => 'خردلي', 'code' => '#FFDB58'],
            ['name' => 'Nude', 'ar_name' => 'لون البشرة', 'code' => '#E3BC9A'],
            ['name' => 'Ocean Blue', 'ar_name' => 'أزرق محيط', 'code' => '#006994'],
            ['name' => 'Ochre', 'ar_name' => 'أصفر ترابي', 'code' => '#CC7722'],
            ['name' => 'Off White', 'ar_name' => 'أبيض عاجي', 'code' => '#FAF9F6'],
            ['name' => 'Olive Green', 'ar_name' => 'أخضر زيتوني', 'code' => '#556B2F'],
            ['name' => 'Orchid', 'ar_name' => 'أوركيد', 'code' => '#DA70D6'],
            ['name' => 'Pearl', 'ar_name' => 'لؤلؤي', 'code' => '#E8E0D5'],
            ['name' => 'Periwinkle', 'ar_name' => 'بنفسجي لافندر', 'code' => '#CCCCFF'],
            ['name' => 'Pistachio', 'ar_name' => 'فستقي', 'code' => '#93C572'],
            ['name' => 'Powder Blue', 'ar_name' => 'أزرق بودرة', 'code' => '#B0E0E6'],
            ['name' => 'Raspberry', 'ar_name' => 'توتي', 'code' => '#E30B5C'],
            ['name' => 'Sage Green', 'ar_name' => 'أخضر مريمية', 'code' => '#87AE73'],
            ['name' => 'Sand', 'ar_name' => 'رملي', 'code' => '#C2B280'],
            ['name' => 'Scarlet', 'ar_name' => 'قرمزي فاتح', 'code' => '#FF2400'],
            ['name' => 'Seafoam Green', 'ar_name' => 'أخضر رغوة البحر', 'code' => '#9FE2BF'],
            ['name' => 'Slate Gray', 'ar_name' => 'رمادي أردوازي', 'code' => '#708090'],
            ['name' => 'Steel Blue', 'ar_name' => 'أزرق فولاذي', 'code' => '#4682B4'],
            ['name' => 'Strawberry', 'ar_name' => 'فراولي', 'code' => '#FC5A8D'],
            ['name' => 'Sunset Orange', 'ar_name' => 'برتقالي غروب', 'code' => '#FD5E53'],
            ['name' => 'Tangerine', 'ar_name' => 'يوسفي', 'code' => '#FF9500'],
            ['name' => 'Taupe', 'ar_name' => 'رمادي بني', 'code' => '#483C32'],
            ['name' => 'Terracotta', 'ar_name' => 'تراكوتا', 'code' => '#E2725B'],
            ['name' => 'Tomato Red', 'ar_name' => 'أحمر طماطم', 'code' => '#FF6347'],
            ['name' => 'Vanilla', 'ar_name' => 'فانيليا', 'code' => '#F3E5AB'],
            ['name' => 'Wine Red', 'ar_name' => 'أحمر نبيذ', 'code' => '#722F37'],
            ['name' => 'Wisteria', 'ar_name' => 'بنفسجي وستيريا', 'code' => '#C9A0DC'],
            ['name' => 'Zinc', 'ar_name' => 'زنك', 'code' => '#7A7A7A'],
        ];

        // Add timestamps to all records
        $colors = array_map(function ($color) use ($now) {
            return array_merge($color, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $colors);

        Color::insert($colors);
    }
}

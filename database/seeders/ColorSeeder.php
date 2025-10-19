<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Seed base colors with translations.
     */
    public function run(): void
    {
        $colors = [
            [
                'hex_code' => '#1F4F9C',
                'translations' => [
                    'en' => ['name' => 'Steel Blue'],
                    'ar' => ['name' => 'أزرق فولاذي'],
                ],
            ],
            [
                'hex_code' => '#C0392B',
                'translations' => [
                    'en' => ['name' => 'Crimson Red'],
                    'ar' => ['name' => 'أحمر قرمزي'],
                ],
            ],
            [
                'hex_code' => '#7F8C8D',
                'translations' => [
                    'en' => ['name' => 'Granite Gray'],
                    'ar' => ['name' => 'رمادي جرانيت'],
                ],
            ],
        ];

        foreach ($colors as $colorData) {
            $color = Color::create($colorData);
            $color->save();
        }
    }
}


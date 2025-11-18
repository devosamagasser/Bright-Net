<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorSpotlightIPTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor IP-Rated Spotlight Datasheet Template',
                'description' => 'Technical specification template for waterproof outdoor spotlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات سبوت لايت خارجي مقاوم للماء',
                'description' => 'قالب المواصفات الفنية لسبوت لايت خارجي مقاوم للماء'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Mounting Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'mounting_type',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Spike Mounted',
                'Surface Base Mounted',
                'Wall Mounted (Outdoor)',
            ],
            'en' => ['label' => 'Mounting Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Adjustable Tilt Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'tilt_angle',
            'position' => 2,
            'is_required' => true,
            'options' => [
                '0°',
                '15°',
                '30°',
                '45°',
                '60°',
                '90°',
            ],
            'en' => ['label' => 'Tilt Angle'],
            'ar' => ['label' => 'زاوية الميل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Housing Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 3,
            'is_required' => true,
            'options' => [
                'Aluminum (Die-cast)',
                'Stainless Steel (316)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Body Color
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'body_color',
            'position' => 4,
            'is_required' => false,
            'options' => [
                'Black',
                'Dark Grey',
                'Silver',
                'White',
                'Custom RAL',
            ],
            'en' => ['label' => 'Body Color'],
            'ar' => ['label' => 'لون الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Dimensions
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 5,
            'is_required' => true,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 6,
            'is_required' => true,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 7,
            'is_required' => true,
            'options' => [
                '10° (Very Narrow Spot)',
                '15°',
                '24°',
                '36°',
                '45°',
                '60° (Wide)',
                'Asymmetric',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 8,
            'is_required' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Luminous Flux (lm)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 9,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 10,
            'is_required' => true,
            'options' => [
                '2700K',
                '3000K',
                '4000K',
                '5000K',
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 11,
            'is_required' => true,
            'options' => [
                'CRI 80+',
                'CRI 90+',
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 12,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '24V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 13,
            'is_required' => true,
            'options' => [
                'IP65',
                'IP66',
                'IP67',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 14,
            'is_required' => true,
            'options' => [
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 15,
            'is_required' => true,
            'options' => [
                '3 Years',
                '5 Years',
                '7 Years',
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

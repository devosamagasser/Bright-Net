<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class InGroundOutdoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'In-Ground Light Datasheet Template',
                'description' => 'Specification template for in-ground recessed outdoor lighting'
            ],
            'ar' => [
                'name' => 'قالب بيانات إن-جراوند',
                'description' => 'قالب المواصفات الفنية للوحدات الأرضية المدفونة'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Shape (Round / Square)
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Round',
                'Square',
            ],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Dimensions
        | Depends on shape
        |--------------------------------------------------------------------------
        */
        // Diameter (for round)
        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 2,
            'is_required' => false,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);


        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);


        // Side (for square)
        $side = $template->fields()->create([
            'type' => 'number',
            'name' => 'side_mm',
            'position' => 3,
            'is_required' => false,
            'en' => ['label' => 'Side (mm)'],
            'ar' => ['label' => 'الضلع (مم)'],
        ]);

        $side->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Square'],
        ]);


        // Depth
        $template->fields()->create([
            'type' => 'number',
            'name' => 'depth_mm',
            'position' => 4,
            'is_required' => true,
            'en' => ['label' => 'Depth (mm)'],
            'ar' => ['label' => 'العمق (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Load Rating (Drive-over capacity)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'load_rating',
            'position' => 5,
            'is_required' => true,
            'options' => [
                'Walk-over',
                'Drive-over 2 Tons',
                'Drive-over 5 Tons',
                'Drive-over 20 Tons',
            ],
            'en' => ['label' => 'Load Rating'],
            'ar' => ['label' => 'قدرة التحمل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Cover / Frame Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'frame_material',
            'position' => 6,
            'is_required' => true,
            'options' => [
                'Stainless Steel (316)',
                'Stainless Steel (304)',
                'Aluminum',
            ],
            'en' => ['label' => 'Frame Material'],
            'ar' => ['label' => 'مادة الإطار'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Glass Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'glass_type',
            'position' => 7,
            'is_required' => true,
            'options' => [
                'Clear Tempered Glass',
                'Frosted Tempered Glass',
                'Anti-slip Glass',
            ],
            'en' => ['label' => 'Glass Type'],
            'ar' => ['label' => 'نوع الزجاج'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 8,
            'is_required' => true,
            'options' => [
                '10°',
                '24°',
                '36°',
                '60°',
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
            'position' => 9,
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
            'position' => 10,
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
            'position' => 11,
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
            'position' => 12,
            'is_required' => true,
            'options' => [
                'CRI 70+',
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
            'position' => 13,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '24V DC',
                '12V DC',
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
            'position' => 14,
            'is_required' => true,
            'options' => [
                'IP67',
                'IP68',
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
            'position' => 15,
            'is_required' => true,
            'options' => [
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'تصنيف مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — Drainage Requirement
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'boolean',
            'name' => 'requires_drainage',
            'position' => 16,
            'is_required' => true,
            'en' => ['label' => 'Requires Drainage System'],
            'ar' => ['label' => 'يحتاج لنظام تصريف'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 15 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 17,
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

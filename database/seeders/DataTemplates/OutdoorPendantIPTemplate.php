<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorPendantIPTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor IP-Rated Pendant Light Datasheet Template',
                'description' => 'Technical specification template for waterproof outdoor pendant luminaires'
            ],
            'ar' => [
                'name' => 'قالب بيانات إضاءة معلقة خارجية مقاومة للماء',
                'description' => 'قالب المواصفات الفنية للوحدات المعلقة الخارجية المقاومة للماء'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Suspended Mounting Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'suspension_type',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Steel Cable',
                'Rod Suspension',
                'Pendant Cord',
                'Chain Suspension',
            ],
            'en' => ['label' => 'Suspension Type'],
            'ar' => ['label' => 'نوع التعليق'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Cable Length
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'cable_length_mm',
            'position' => 2,
            'is_required' => false,
            'en' => ['label' => 'Cable Length (mm)'],
            'ar' => ['label' => 'طول السلك (مم)'],
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
                'Aluminum (Extruded)',
                'Stainless Steel (304)',
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
                'White',
                'Silver',
                'Bronze',
                'Custom RAL',
            ],
            'en' => ['label' => 'Body Color'],
            'ar' => ['label' => 'لون الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Diffuser Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'diffuser_type',
            'position' => 5,
            'is_required' => true,
            'options' => [
                'Opal Glass',
                'Clear Glass',
                'Frosted Glass',
                'PC Diffuser',
            ],
            'en' => ['label' => 'Diffuser Type'],
            'ar' => ['label' => 'نوع الناشر'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Shape
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 6,
            'is_required' => true,
            'options' => [
                'Cylinder',
                'Globe',
                'Cone',
                'Bell',
                'Dome',
            ],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Dimensions
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 7,
            'is_required' => true,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 8,
            'is_required' => true,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 9,
            'is_required' => false,
            'options' => [
                '120° (Wide)',
                '90°',
                '60°',
                '40°',
                'Asymmetric',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 10,
            'is_required' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — Luminous Flux (lm)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 11,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 12,
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
        | 12 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 13,
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
        | 13 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 14,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '24V DC',
                '48V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 15,
            'is_required' => true,
            'options' => [
                'IP65',
                'IP66',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 15 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 16,
            'is_required' => true,
            'options' => [
                'IK07',
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 16 — Warranty
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

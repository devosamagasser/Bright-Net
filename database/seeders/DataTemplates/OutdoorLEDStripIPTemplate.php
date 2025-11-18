<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorLEDStripIPTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor LED Flex/Strip (IP Rated) Datasheet Template',
                'description' =>
                    'Technical specification template for waterproof outdoor LED strip and flex luminaires'
            ],
            'ar' => [
                'name' => 'قالب بيانات ليد سترب خارجي مقاوم للماء',
                'description' =>
                    'قالب المواصفات الفنية لأشرطة الليد الخارجية المقاومة للماء'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Strip Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'strip_type',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Silicone Extrusion (Neon Flex)',
                'PU Coated LED Strip',
                'PVC Encapsulated LED Strip',
                'Mini Neon Flex',
                'Side-Bend Neon Flex',
                'Top-Bend Neon Flex',
            ],
            'en' => ['label' => 'Strip Type'],
            'ar' => ['label' => 'نوع الشريط'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Bending Direction
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'bending_type',
            'position' => 2,
            'is_required' => true,
            'options' => [
                'Top-Bend',
                'Side-Bend',
                '3D Bend',
            ],
            'en' => ['label' => 'Bending Direction'],
            'ar' => ['label' => 'اتجاه الانحناء'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Power Consumption
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w_per_meter',
            'position' => 3,
            'is_required' => true,
            'en' => ['label' => 'Power (W/m)'],
            'ar' => ['label' => 'القدرة (واط/متر)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 4,
            'is_required' => true,
            'options' => [
                '24V DC',
                '48V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — LED Density (LEDs per meter)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'led_density',
            'position' => 5,
            'is_required' => true,
            'en' => ['label' => 'LED Density (LEDs/m)'],
            'ar' => ['label' => 'كثافة الليد (LED/متر)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Cuttable Length
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'cut_length_mm',
            'position' => 6,
            'is_required' => true,
            'en' => ['label' => 'Cutting Length (mm)'],
            'ar' => ['label' => 'مسافة القص (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Max Length per Run
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'max_run_length_m',
            'position' => 7,
            'is_required' => true,
            'en' => ['label' => 'Max Run Length (m)'],
            'ar' => ['label' => 'الطول الأقصى للتشغيل (متر)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — CCT / Color Modes
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 8,
            'is_required' => true,
            'options' => [
                '2700K',
                '3000K',
                '3500K',
                '4000K',
                '5000K',
                'RGB',
                'RGBW',
                'Tunable White (Dynamic)',
            ],
            'en' => ['label' => 'CCT / Color Mode'],
            'ar' => ['label' => 'درجة حرارة اللون / وضع اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 9,
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
        | 10 — Beam Type (Strip)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_type',
            'position' => 10,
            'is_required' => true,
            'options' => [
                '120° Standard',
                '180° Wide',
                'Dotless Diffused',
            ],
            'en' => ['label' => 'Beam Type'],
            'ar' => ['label' => 'نوع الشعاع'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 11,
            'is_required' => true,
            'options' => [
                'IP65',
                'IP67',
                'IP68',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — IK Rating (if flexible extrusion)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 12,
            'is_required' => false,
            'options' => [
                'IK05',
                'IK07',
                'IK08',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Control Options
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'control_options',
            'position' => 13,
            'is_required' => false,
            'options' => [
                'Non-Dimmable',
                '1-10V',
                '0-10V',
                'DALI',
                'DMX (for RGB/RGBW)',
                'PWM',
            ],
            'en' => ['label' => 'Control Options'],
            'ar' => ['label' => 'خيارات التحكم'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 14,
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

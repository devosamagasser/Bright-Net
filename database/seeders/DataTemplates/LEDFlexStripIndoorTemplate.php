<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class LEDFlexStripIndoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'LED Flex/Strip Indoor Datasheet Template',
                'description' => 'Specification template for indoor LED strips and flexible lighting'
            ],
            'ar' => [
                'name' => 'قالب بيانات شريط LED داخلي',
                'description' => 'قالب المواصفات الفنية لشرائط LED الداخلية والإضاءة المرنة'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['12V DC', '24V DC'],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — LED Density (LED per meter)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'led_density',
            'position' => 2,
            'is_required' => true,
            'options' => [
                '30 LED/m',
                '60 LED/m',
                '90 LED/m',
                '120 LED/m',
                '180 LED/m',
                '240 LED/m',
            ],
            'en' => ['label' => 'LED Density'],
            'ar' => ['label' => 'عدد الليد لكل متر'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Power Consumption (W/m)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_per_meter',
            'position' => 3,
            'is_required' => true,
            'en' => ['label' => 'Power (W/m)'],
            'ar' => ['label' => 'القدرة (واط/م)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Luminous Flux (lm/m)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux_per_meter',
            'position' => 4,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux (lm/m)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن/م)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — CCT / RGB / RGBW
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct_or_color_type',
            'position' => 5,
            'is_required' => true,
            'options' => [
                '2700K',
                '3000K',
                '3500K',
                '4000K',
                '5000K',
                'RGB',
                'RGBW',
                'Tunable White (2700–6500K)',
            ],
            'en' => ['label' => 'Light Type'],
            'ar' => ['label' => 'نوع الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Cuttable Length
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cut_length',
            'position' => 6,
            'is_required' => true,
            'options' => [
                '25mm',
                '50mm',
                '100mm',
                '125mm',
                '166mm',
            ],
            'en' => ['label' => 'Cuttable Every'],
            'ar' => ['label' => 'يمكن القص كل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — PCB Width (mm)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'pcb_width',
            'position' => 7,
            'is_required' => true,
            'en' => ['label' => 'PCB Width (mm)'],
            'ar' => ['label' => 'عرض الـ PCB (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Color Rendering (CRI)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 8,
            'is_required' => true,
            'options' => ['CRI 80+', 'CRI 90+', 'CRI 95+'],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Beam Angle (Fixed 120° usually)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 9,
            'is_required' => true,
            'options' => ['120°'],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — IP Rating (Indoor only)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 10,
            'is_required' => true,
            'options' => ['IP20'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — Max Run Length (meters)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'max_run_length',
            'position' => 11,
            'is_required' => true,
            'en' => ['label' => 'Max Continuous Run (m)'],
            'ar' => ['label' => 'أقصى طول توصيل متصل (متر)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — Mounting Method
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'mounting_method',
            'position' => 12,
            'is_required' => true,
            'options' => [
                'Adhesive Backing',
                'Aluminum Profile',
                'Clips',
            ],
            'en' => ['label' => 'Mounting Method'],
            'ar' => ['label' => 'طريقة التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 13,
            'is_required' => true,
            'options' => ['2 Years', '3 Years', '5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

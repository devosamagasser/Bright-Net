<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorEmergencyExitTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id'   => $subcategoryId,
            'type'             => 'product',
            'en' => [
                'name' => 'Outdoor Emergency / Exit Light Datasheet Template',
                'description' =>
                    'Technical specification template for outdoor-rated emergency and exit luminaires'
            ],
            'ar' => [
                'name' => 'قالب بيانات إضاءة طوارئ / مخارج خارجية',
                'description' =>
                    'قالب المواصفات الفنية لوحدات الطوارئ ومخارج الطوارئ الخارجية المقاومة للعوامل الجوية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Type (Emergency or Exit Sign)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'unit_type',
            'position'    => 1,
            'is_required' => true,
            'options'     => [
                'Emergency Light',
                'Exit Sign',
                'Bulkhead Emergency',
            ],
            'en' => ['label' => 'Unit Type'],
            'ar' => ['label' => 'نوع وحدة الطوارئ'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Mode of Operation
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'mode_of_operation',
            'position'    => 2,
            'is_required' => true,
            'options'     => [
                'Maintained (Always On)',
                'Non-Maintained (On Only When Power Fails)',
                'Combined (Maintained + Emergency)',
            ],
            'en' => ['label' => 'Mode of Operation'],
            'ar' => ['label' => 'وضع التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Battery Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'battery_type',
            'position'    => 3,
            'is_required' => true,
            'options'     => [
                'Ni-Cd',
                'Ni-MH',
                'Li-Ion',
                'LiFePO4 (Long Life)',
            ],
            'en' => ['label' => 'Battery Type'],
            'ar' => ['label' => 'نوع البطارية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Autonomy (Backup Duration)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'autonomy',
            'position'    => 4,
            'is_required' => true,
            'options'     => [
                '1 Hour',
                '2 Hours',
                '3 Hours',
            ],
            'en' => ['label' => 'Backup Duration'],
            'ar' => ['label' => 'مدة التشغيل الاحتياطي'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Charging Time
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'number',
            'name'        => 'charging_time_hours',
            'position'    => 5,
            'is_required' => false,
            'en' => ['label' => 'Charging Time (Hours)'],
            'ar' => ['label' => 'مدة الشحن (ساعات)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Luminous Flux (Emergency Mode)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'number',
            'name'        => 'emergency_lumen',
            'position'    => 6,
            'is_required' => false,
            'en' => ['label' => 'Luminous Flux (Emergency Mode) (lm)'],
            'ar' => ['label' => 'التدفق الضوئي في وضع الطوارئ (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — LED Source Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'led_source_type',
            'position'    => 7,
            'is_required' => false,
            'options'     => [
                'Integrated LED',
                'High-Efficiency LED Module',
            ],
            'en' => ['label' => 'LED Source Type'],
            'ar' => ['label' => 'نوع مصدر الضوء'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Viewing Distance (for Exit Sign)
        |--------------------------------------------------------------------------
        */
        $view = $template->fields()->create([
            'type'        => 'select',
            'name'        => 'viewing_distance',
            'position'    => 8,
            'is_required' => false,
            'options'     => [
                '24m',
                '30m',
                '36m',
                '40m',
            ],
            'en' => ['label' => 'Viewing Distance'],
            'ar' => ['label' => 'مسافة الرؤية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Pictogram Options (Exit Sign only)
        |--------------------------------------------------------------------------
        */
        $pics = $template->fields()->create([
            'type'        => 'multiselect',
            'name'        => 'pictograms',
            'position'    => 9,
            'is_required' => false,
            'options'     => [
                'Left Arrow',
                'Right Arrow',
                'Down Arrow',
                'Up Arrow',
                'Running Man',
            ],
            'en' => ['label' => 'Pictograms'],
            'ar' => ['label' => 'البيكتورجرام'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Dependencies: Pictogram/Viewing Distance only when unit_type = Exit Sign
        |--------------------------------------------------------------------------
        */
        $pics->dependency()->create([
            'depends_on_field_id' => $view->id,
            'values' => ['Exit Sign'],
        ]);
        

        /*
        |--------------------------------------------------------------------------
        | 10 — IP Rating (Outdoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'ip_rating',
            'position'    => 10,
            'is_required' => true,
            'options'     => [
                'IP65',
                'IP66',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'ik_rating',
            'position'    => 11,
            'is_required' => true,
            'options'     => [
                'IK07',
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'input_voltage',
            'position'    => 12,
            'is_required' => true,
            'options'     => [
                '220-240V AC',
                '100-277V AC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Test Function
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'test_function',
            'position'    => 13,
            'is_required' => false,
            'options'     => [
                'Manual Test Button',
                'Self-Test',
                'Automatic Test System',
            ],
            'en' => ['label' => 'Test Function'],
            'ar' => ['label' => 'وظيفة الاختبار'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type'        => 'select',
            'name'        => 'warranty',
            'position'    => 14,
            'is_required' => true,
            'options'     => [
                '2 Years',
                '3 Years',
                '5 Years',
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

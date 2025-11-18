<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class EmergencyExitTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Emergency / Exit Light Datasheet Template',
                'description' => 'Specification template for emergency and exit lighting fixtures'
            ],
            'ar' => [
                'name' => 'قالب بيانات إضاءة الطوارئ والخروج',
                'description' => 'قالب المواصفات الفنية لوحدات الطوارئ ولوحات مخرج الطوارئ'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Emergency Type (Maintained vs Non-maintained)
        |--------------------------------------------------------------------------
        */
        $mode = $template->fields()->create([
            'type' => 'select',
            'name' => 'emergency_mode',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Maintained',
                'Non-Maintained',
                'Combined Mode',
            ],
            'en' => ['label' => 'Emergency Operation Mode'],
            'ar' => ['label' => 'نمط تشغيل الطوارئ'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Installation Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 2,
            'is_required' => true,
            'options' => [
                'Surface Mounted',
                'Recessed',
                'Wall Mounted',
                'Ceiling Mounted',
                'Suspended',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التثبيت'],
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
                'Polycarbonate (PC)',
                'ABS',
                'Aluminum',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Pictogram / Arrow Direction (For Exit signs)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'pictogram_direction',
            'position' => 4,
            'is_required' => false,
            'options' => [
                'None',
                'Left Arrow',
                'Right Arrow',
                'Up Arrow',
                'Down Arrow',
                'Running Man Symbol',
            ],
            'en' => ['label' => 'Pictogram Direction'],
            'ar' => ['label' => 'اتجاه السهم / الرمز'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Emergency Duration
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'emergency_duration',
            'position' => 5,
            'is_required' => true,
            'options' => [
                '1 Hour',
                '2 Hours',
                '3 Hours',
            ],
            'en' => ['label' => 'Emergency Duration'],
            'ar' => ['label' => 'مدة التشغيل في الطوارئ'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Battery Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'battery_type',
            'position' => 6,
            'is_required' => true,
            'options' => [
                'Ni-Cd',
                'Ni-MH',
                'Li-ion',
                'LiFePO4',
            ],
            'en' => ['label' => 'Battery Type'],
            'ar' => ['label' => 'نوع البطارية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Battery Capacity (mAh)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'battery_capacity',
            'position' => 7,
            'is_required' => true,
            'en' => ['label' => 'Battery Capacity (mAh)'],
            'ar' => ['label' => 'سعة البطارية (mAh)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Charging Time
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'charging_time_hours',
            'position' => 8,
            'is_required' => true,
            'en' => ['label' => 'Charging Time (Hours)'],
            'ar' => ['label' => 'وقت الشحن (ساعات)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Lumens (Normal Mode)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux_normal',
            'position' => 9,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux - Normal Mode (lm)'],
            'ar' => ['label' => 'التدفق الضوئي - الوضع العادي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — Lumens (Emergency Mode)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux_emergency',
            'position' => 10,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux - Emergency Mode (lm)'],
            'ar' => ['label' => 'التدفق الضوئي - وضع الطوارئ (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 11,
            'is_required' => true,
            'options' => ['220-240V AC'],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — IP Rating (Indoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 12,
            'is_required' => true,
            'options' => ['IP20', 'IP40'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Standards Compliance
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'standards',
            'position' => 13,
            'is_required' => false,
            'options' => [
                'EN 1838',
                'EN 60598-2-22',
                'IEC 61347',
                'UL 924',
            ],
            'en' => ['label' => 'Compliance Standards'],
            'ar' => ['label' => 'معايير المطابقة'],
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
            'options' => ['2 Years', '3 Years', '5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

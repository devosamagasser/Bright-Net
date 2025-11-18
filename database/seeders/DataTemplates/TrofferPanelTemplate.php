<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class TrofferPanelTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Panel / Troffer Datasheet Template',
                'description' => 'Specification template for indoor LED panel and troffer fixtures'
            ],
            'ar' => [
                'name' => 'قالب بيانات بانل/تروفر',
                'description' => 'قالب المواصفات الفنية لوحدات البانل والتروفر الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Installation Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Recessed (T-Grid)',
                'Recessed (Plasterboard)',
                'Surface Mounted',
                'Suspended (Pendant)',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Panel Size (Standard Dimensions)
        |--------------------------------------------------------------------------
        */
        $size = $template->fields()->create([
            'type' => 'select',
            'name' => 'panel_size',
            'position' => 2,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '600 x 600 mm',
                '300 x 1200 mm',
                '600 x 1200 mm',
                '300 x 600 mm',
                'Other (Custom)',
            ],
            'en' => ['label' => 'Panel Size'],
            'ar' => ['label' => 'مقاس اللوحة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Custom Size (only if "Other")
        |--------------------------------------------------------------------------
        */
        $length = $template->fields()->create([
            'type' => 'number',
            'name' => 'custom_length',
            'position' => 3,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Custom Length (mm)'],
            'ar' => ['label' => 'الطول المخصص (مم)'],
        ]);

        $length->dependency()->create([
            'depends_on_field_id' => $size->id,
            'values' => ['Other (Custom)'],
        ]);

        $width = $template->fields()->create([
            'type' => 'number',
            'name' => 'custom_width',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Custom Width (mm)'],
            'ar' => ['label' => 'العرض المخصص (مم)'],
        ]);

        $width->dependency()->create([
            'depends_on_field_id' => $size->id,
            'values' => ['Other (Custom)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Housing Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 5,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Aluminum (Extruded)',
                'Aluminum (Die-cast)',
                'Steel Sheet',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optic Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 6,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Opal / Frosted (Uniform)',
                'Microprismatic (UGR<19)',
                'Clear Cover',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — UGR Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ugr_rating',
            'position' => 7,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'UGR <16',
                'UGR <19',
                'UGR <22',
            ],
            'en' => ['label' => 'UGR Rating'],
            'ar' => ['label' => 'تصنيف الوهج'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Input Power
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'position' => 8,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 9,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                '220-240V AC',
                '100-277V AC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Luminous Flux
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 11,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '3000K',
                '3500K',
                '4000K',
                '5000K',
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'CRI 80+',
                'CRI 90+',
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — Flicker Free
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'boolean',
            'name' => 'flicker_free',
            'position' => 13,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Flicker Free'],
            'ar' => ['label' => 'خالي من الوميض'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 14,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20', 'IP40'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
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
            'is_filterable' => false,
            'options' => ['2 Years', '3 Years', '5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

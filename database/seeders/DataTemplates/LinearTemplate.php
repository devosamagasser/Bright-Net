<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class LinearTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Linear Lighting Datasheet Template',
                'description' => 'Technical datasheet template for indoor linear luminaires'
            ],
            'ar' => [
                'name' => 'قالب بيانات الإضاءة الخطية',
                'description' => 'قالب المواصفات الفنية للوحدات الخطية الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Installation Type
        |--------------------------------------------------------------------------
        */
        $installation = $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Surface Mounted',
                'Recessed (Trimmed)',
                'Recessed (Trimless)',
                'Suspended (Pendant)',
                'Suspended (Catenary)',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Housing Material
        |--------------------------------------------------------------------------
        */
        $housing = $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 2,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Aluminum (Extruded)',
                'Aluminum (Die-cast)',
                'Polycarbonate (PC)',
                'PMMA (Acrylic)',
                'Stainless Steel (304)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Shape (Linear / Rectangular)
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 3,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Linear',
                'Rectangular',
            ],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions (Linear / Rectangular)
        |--------------------------------------------------------------------------
        */
        $length = $template->fields()->create([
            'type' => 'number',
            'name' => 'length_mm',
            'position' => 4,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);

        $width = $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 5,
            'is_required' => true,
            'is_filterable' => false,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $height = $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 6,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Cutout (only when Recessed)
        |--------------------------------------------------------------------------
        */
        $cutoutLength = $template->fields()->create([
            'type' => 'number',
            'name' => 'cutout_length',
            'position' => 7,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Cutout Length (mm)'],
            'ar' => ['label' => 'طول فتحة التركيب (مم)'],
        ]);

        $cutoutLength->dependency()->create([
            'depends_on_field_id' => $installation->id,
            'values' => ['Recessed (Trimmed)', 'Recessed (Trimless)'],
        ]);

        $cutoutWidth = $template->fields()->create([
            'type' => 'number',
            'name' => 'cutout_width',
            'position' => 8,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Cutout Width (mm)'],
            'ar' => ['label' => 'عرض فتحة التركيب (مم)'],
        ]);

        $cutoutWidth->dependency()->create([
            'depends_on_field_id' => $installation->id,
            'values' => ['Recessed (Trimmed)', 'Recessed (Trimless)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Diffuser / Optic
        |--------------------------------------------------------------------------
        */
        $optic = $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 9,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Opal / Frosted',
                'Microprismatic',
                'Asymmetric Lens',
                'Baffle / Darklight',
                'Clear Cover',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Input Power
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Voltage
        |--------------------------------------------------------------------------
        */
        $voltage = $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 11,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                '220-240V AC',
                '100-277V AC',
                '24V DC',
                '48V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '2700K','3000K','3500K','4000K','5000K','5700K','6500K'
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — CRI
        |--------------------------------------------------------------------------
        */
        $cri = $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['CRI 80+','CRI 90+','CRI 95+'],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — IP Rating
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 14,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20','IP40','IP44'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — Warranty
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 15,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['2 Years','3 Years','5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class PendantTemplate
{
    public function build(int $subcategoryId): void
    {
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Pendant Lighting Datasheet Template',
                'description' => 'Technical datasheet for indoor suspended pendant fixtures'
            ],
            'ar' => [
                'name' => 'قالب بيانات الإضاءة المعلقة',
                'description' => 'قالب مواصفات فنية للإضاءات المعلقة الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Installation Type (Always Pendant)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
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
        $template->fields()->create([
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
                'Glass',
                'Stainless Steel (304)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Shape
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 3,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Round',
                'Linear',
                'Square',
                'Rectangular',
            ],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions (conditional)
        |--------------------------------------------------------------------------
        */

        // Diameter (Round)
        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);

        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);

        // Linear dimensions
        $length = $template->fields()->create([
            'type' => 'number',
            'name' => 'length_mm',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => true,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);

        $length->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Linear', 'Rectangular'],
        ]);

        $width = $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 6,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $width->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Rectangular', 'Square'],
        ]);

        // Height (always used)
        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 7,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Cable Length
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'cable_length_mm',
            'position' => 8,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Cable Length (mm)'],
            'ar' => ['label' => 'طول السلك (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Optic Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 9,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Opal / Frosted',
                'Microprismatic',
                'Baffle / Darklight',
                'Clear Cover',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Electrical (Power, Voltage)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        $template->fields()->create([
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
        | 8 — Photometric (CCT, CRI, Lumen output)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '2700K','3000K','3500K','4000K','5000K'
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['CRI 80+','CRI 90+'],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 14,
            'is_required' => false,
            'is_filterable' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — IP + Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 15,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20','IP40'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 16,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['2 Years','3 Years','5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

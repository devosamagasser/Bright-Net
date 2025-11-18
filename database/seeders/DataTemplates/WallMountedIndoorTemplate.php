<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class WallMountedIndoorTemplate
{
    public function build(int $subcategoryId): void
    {
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Indoor Wall Mounted Datasheet Template',
                'description' => 'Specification template for indoor wall-mounted decorative and functional lighting'
            ],
            'ar' => [
                'name' => 'قالب بيانات إضاءة جدارية داخلية',
                'description' => 'قالب المواصفات الفنية للوحدات الجدارية الداخلية الزخرفية والعملية'
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
                'Surface Mounted',
                'Recessed Mounted',
                'Semi-Recessed',
                'Bracket Mounted',
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
            'options' => [
                'Aluminum (Extruded)',
                'Aluminum (Die-cast)',
                'Steel',
                'Glass',
                'Polycarbonate (PC)',
                'Fabric (Decorative)',
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
            'options' => [
                'Round',
                'Square',
                'Rectangular',
                'Linear',
                'Decorative Form',
            ],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions (Conditional)
        |--------------------------------------------------------------------------
        */
        // Round Diameter
        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 4,
            'is_required' => false,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);

        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);

        // Rectangular / Linear Length
        $length = $template->fields()->create([
            'type' => 'number',
            'name' => 'length_mm',
            'position' => 5,
            'is_required' => false,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);

        $length->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Rectangular', 'Linear'],
        ]);

        // Shared Width & Height
        $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 6,
            'is_required' => false,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 7,
            'is_required' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Lighting Direction
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'light_direction',
            'position' => 8,
            'is_required' => true,
            'options' => [
                'Up Light',
                'Down Light',
                'Up & Down',
                'Indirect Light',
                'Direct Light',
                'Direct / Indirect Combo',
            ],
            'en' => ['label' => 'Lighting Direction'],
            'ar' => ['label' => 'اتجاه الإضاءة'],
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
            'options' => [
                'Opal Diffuser',
                'Frosted Glass',
                'Transparent Glass',
                'Microprismatic',
                'Indirect (Hidden Source)',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Power
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
        | 8 — Luminous Flux
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
        | 9 — CCT
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
                '3500K',
                '4000K',
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
            'position' => 13,
            'is_required' => true,
            'options' => [
                'CRI 80+',
                'CRI 90+',
                'CRI 95+',
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
            'position' => 14,
            'is_required' => true,
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
        | 12 — IP Rating (Indoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 15,
            'is_required' => true,
            'options' => [
                'IP20',
                'IP40',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 16,
            'is_required' => true,
            'options' => [
                '2 Years',
                '3 Years',
                '5 Years',
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

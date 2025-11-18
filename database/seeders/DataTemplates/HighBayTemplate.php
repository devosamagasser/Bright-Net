<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class HighBayTemplate
{
    public function build(int $subcategoryId): void
    {
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'High Bay Datasheet Template',
                'description' => 'Industrial high-bay lighting specification template'
            ],
            'ar' => [
                'name' => 'قالب بيانات هاي باي',
                'description' => 'قالب المواصفات الفنية لكشافات الهاي باي الصناعية'
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
                'Hook Mounted',
                'Pendant Mounted',
                'Surface Mounted Bracket',
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
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
                'PC + Aluminum Mix',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Heat Sink Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'heat_sink_material',
            'position' => 3,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Aluminum',
                'Aluminum + Copper Core',
            ],
            'en' => ['label' => 'Heat Sink Material'],
            'ar' => ['label' => 'مادة المشتت الحراري'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Diameter
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter_mm',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Height
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Reflector Type (Optional)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'reflector_type',
            'position' => 6,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'None',
                'Aluminum Reflector',
                'PC Reflector (Clear)',
                'PC Reflector (Frosted)',
            ],
            'en' => ['label' => 'Reflector Type'],
            'ar' => ['label' => 'نوع العاكس'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 7,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '60°',
                '90°',
                '120°',
                'Asymmetric',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Power (Wattage)
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
        | 9 — Input Voltage
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
                '347V AC (Industrial)',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — Luminous Flux (High Output)
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
        | 11 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 11,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '4000K',
                '5000K',
                '5700K',
                '6500K',
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
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
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
        | 13 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'IP65',
                'IP66',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 14,
            'is_required' => false,
            'is_filterable' => true,
            'options' => [
                'IK05',
                'IK07',
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'تصنيف مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 15 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 15,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['3 Years', '5 Years', '7 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorDownlightIPTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor IP-Rated Downlight Datasheet Template',
                'description' => 'Technical specification template for waterproof outdoor downlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات داون لايت خارجي مقاوم للماء',
                'description' => 'قالب المواصفات الفنية لداون لايت خارجي مقاوم للماء'
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
            'options' => [
                'Recessed',
                'Surface Mounted',
                'Trimless (Outdoor)',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Cutout Diameter
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'cutout_diameter_mm',
            'position' => 2,
            'is_required' => true,
            'en' => ['label' => 'Cutout Diameter (mm)'],
            'ar' => ['label' => 'مقاس فتحة التركيب (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Fixture Diameter
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'fixture_diameter_mm',
            'position' => 3,
            'is_required' => true,
            'en' => ['label' => 'Fixture Diameter (mm)'],
            'ar' => ['label' => 'قطر الوحدة (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Housing Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 4,
            'is_required' => true,
            'options' => [
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
                'Stainless Steel (316)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optics / Lens
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 5,
            'is_required' => true,
            'options' => [
                'Clear Glass',
                'Frosted Glass',
                'PC Lens',
                'Anti-glare Lens',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 6,
            'is_required' => true,
            'options' => [
                '15°',
                '24°',
                '36°',
                '60°',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 7,
            'is_required' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — Luminous Flux (lm)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 8,
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
            'position' => 9,
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
        | 10 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 10,
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
        | 11 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 11,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '24V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — IP Rating (Outdoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 12,
            'is_required' => true,
            'options' => [
                'IP65',
                'IP66',
                'IP67',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 13,
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

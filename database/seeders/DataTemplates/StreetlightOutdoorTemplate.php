<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class StreetlightOutdoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Streetlight Datasheet Template',
                'description' => 'Technical specification template for LED street lighting fixtures'
            ],
            'ar' => [
                'name' => 'قالب بيانات إنارة الشوارع',
                'description' => 'قالب المواصفات الفنية لكشافات إنارة الشوارع'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Installation / Mounting Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'mounting_type',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Pole Top (42–60mm)',
                'Pole Top (76mm)',
                'Side-entry Arm',
                'Adjustable Bracket',
            ],
            'en' => ['label' => 'Mounting Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Adjustable Tilt Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'tilt_angle',
            'position' => 2,
            'is_required' => false,
            'options' => [
                '-10°',
                '0°',
                '5°',
                '10°',
                '15°',
                '20°',
            ],
            'en' => ['label' => 'Tilt Angle'],
            'ar' => ['label' => 'زاوية الميل'],
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
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'length_mm',
            'position' => 4,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 5,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 6,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optical Distribution (Road Classes)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_distribution',
            'position' => 7,
            'is_required' => true,
            'options' => [
                'Type II',
                'Type III',
                'Type IV',
                'Type V',
                'Batwing',
                'Asymmetric Roadway',
                'ME3',
                'ME4',
                'CE',
                'S5',
            ],
            'en' => ['label' => 'Optic Distribution'],
            'ar' => ['label' => 'توزيع بصري'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Lens Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'lens_type',
            'position' => 8,
            'is_required' => true,
            'options' => [
                'PC Lens',
                'Glass Lens',
                'Tempered Glass',
            ],
            'en' => ['label' => 'Lens Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 9,
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
            'position' => 10,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9 — Lumen Efficiency (lm/W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'efficacy_lm_w',
            'position' => 11,
            'is_required' => true,
            'en' => ['label' => 'Luminous Efficacy (lm/W)'],
            'ar' => ['label' => 'الكفاءة الضوئية (لومن/واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — CCT
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
        | 11 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 13,
            'is_required' => true,
            'options' => [
                'CRI 70+',
                'CRI 80+',
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — Smart/Control Options
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'control_options',
            'position' => 14,
            'is_required' => false,
            'options' => [
                'Non-Dimmable',
                '1-10V',
                '0-10V',
                'DALI',
                'NEMA Socket',
                'Zhaga Book 18',
                'Photocell Sensor',
                'Motion Sensor',
            ],
            'en' => ['label' => 'Control Options'],
            'ar' => ['label' => 'خيارات التحكم'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Surge Protection (kV)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'surge_protection',
            'position' => 15,
            'is_required' => true,
            'options' => [
                '4kV',
                '6kV',
                '10kV',
                '20kV (High Protection)',
            ],
            'en' => ['label' => 'Surge Protection'],
            'ar' => ['label' => 'حماية من ارتفاع الجهد'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 16,
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
        | 15 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 17,
            'is_required' => true,
            'options' => [
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 16 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 18,
            'is_required' => true,
            'options' => [
                '5 Years',
                '7 Years',
                '10 Years',
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

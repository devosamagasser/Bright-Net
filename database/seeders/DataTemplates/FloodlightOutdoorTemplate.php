<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class FloodlightOutdoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor Floodlight Datasheet Template',
                'description' => 'Technical specification template for outdoor LED floodlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات كشاف خارجي',
                'description' => 'قالب المواصفات الفنية للكشافات الخارجية'
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
                'Bracket Mounted',
                'Pole Arm Mounted',
                'Surface Mounted',
                'Wall Mounted',
                'U-Bracket Adjustable',
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
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Dimensions
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 3,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 4,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'depth_mm',
            'position' => 5,
            'en' => ['label' => 'Depth (mm)'],
            'ar' => ['label' => 'العمق (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Beam Angle
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 6,
            'is_required' => true,
            'options' => [
                '30°',
                '60°',
                '90°',
                '120°',
                'Asymmetric',
                'Double Asymmetric',
                'Sports Optic',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optic Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 7,
            'is_required' => true,
            'options' => [
                'Glass Lens',
                'PC Lens',
                'Asymmetric Lens',
                'Clear Glass',
                'Tempered Glass',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 8,
            'is_required' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Luminous Flux (lm)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 9,
            'is_required' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'التدفق الضوئي (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 10,
            'is_required' => true,
            'options' => [
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
        | 9 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 11,
            'is_required' => true,
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
        | 10 — Surge Protection
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'surge_protection',
            'position' => 12,
            'is_required' => true,
            'options' => [
                '2kV',
                '4kV',
                '6kV',
                '10kV',
            ],
            'en' => ['label' => 'Surge Protection'],
            'ar' => ['label' => 'حماية من ارتفاع الجهد'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — IP Rating (Outdoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 13,
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
        | 12 — IK Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 14,
            'is_required' => true,
            'options' => [
                'IK07',
                'IK08',
                'IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'تصنيف مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Input Voltage (Outdoor)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 15,
            'is_required' => true,
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
        | 14 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 16,
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

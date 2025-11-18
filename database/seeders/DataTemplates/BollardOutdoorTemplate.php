<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class BollardOutdoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor Bollard Datasheet Template',
                'description' => 'Specification template for outdoor pathway and landscape bollards'
            ],
            'ar' => [
                'name' => 'قالب بيانات بولارد خارجي',
                'description' => 'قالب المواصفات الفنية لأعمدة الإضاءة الخارجية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Bollard Height
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 1,
            'is_required' => true,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
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
                'Stainless Steel (304)',
                'Stainless Steel (316 - Marine Grade)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Mounting Base Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'mounting_type',
            'position' => 3,
            'is_required' => true,
            'options' => [
                'Surface Mounted',
                'Ground Burial (Direct Burial)',
                'Anchor Bolts Plate',
            ],
            'en' => ['label' => 'Mounting Type'],
            'ar' => ['label' => 'نوع التثبيت'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Beam Distribution
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_distribution',
            'position' => 4,
            'is_required' => true,
            'options' => [
                '360° Symmetric',
                '180° Bi-directional',
                '90°',
                'Asymmetric Pathway',
            ],
            'en' => ['label' => 'Beam Distribution'],
            'ar' => ['label' => 'زاوية توزيع الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optic / Diffuser Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 5,
            'is_required' => true,
            'options' => [
                'Opal Diffuser',
                'Clear Diffuser',
                'Frosted Diffuser',
                'Louver (Anti-glare)',
            ],
            'en' => ['label' => 'Diffuser Type'],
            'ar' => ['label' => 'نوع الناشر'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Power (W)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'power_w',
            'position' => 6,
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
            'position' => 7,
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
            'position' => 8,
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
        | 9 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 9,
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
        | 10 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 10,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '24V DC (Low Voltage)',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 11,
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
            'position' => 12,
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
        | 13 — Anti-Corrosion Treatment
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'corrosion_treatment',
            'position' => 13,
            'is_required' => true,
            'options' => [
                'Powder Coated',
                'Marine Grade Coating',
                'Anodized',
            ],
            'en' => ['label' => 'Anti-Corrosion Treatment'],
            'ar' => ['label' => 'معالجة مقاومة التآكل'],
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

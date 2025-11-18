<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class WallPackOutdoorTemplate
{
    public function build(int $subcategoryId): void
    {
        // Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor Wall Pack Datasheet Template',
                'description' => 'Technical specification template for outdoor LED wall packs'
            ],
            'ar' => [
                'name' => 'قالب بيانات وول باك خارجي',
                'description' => 'قالب المواصفات الفنية لوحدات الإضاءة الجدارية الخارجية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Housing Material
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 1,
            'is_required' => true,
            'options' => [
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
                'Polycarbonate (PC)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2 — Installation Type (Always wall)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 2,
            'is_required' => true,
            'options' => [
                'Wall Mounted (Surface)',
                'Wall Mounted (Recessed)',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التركيب'],
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
        | 4 — Light Distribution Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'distribution',
            'position' => 6,
            'is_required' => true,
            'options' => [
                'Full Cutoff',
                'Semi Cutoff',
                'Forward Throw',
                'Wide Distribution',
            ],
            'en' => ['label' => 'Light Distribution'],
            'ar' => ['label' => 'توزيع الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Power (W)
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
        | 6 — Luminous Flux (lm)
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
        | 7 — Optic / Diffuser
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 9,
            'is_required' => true,
            'options' => [
                'Clear Glass',
                'Frosted Glass',
                'PC Lens',
                'Asymmetric Lens',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
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
        | 10 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 12,
            'options' => [
                '220-240V AC',
                '100-277V AC',
            ],
            'is_required' => true,
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
            'position' => 13,
            'is_required' => true,
            'options' => [
                'IP54',
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
        | 13 — Surge Protection
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'surge_protection',
            'position' => 15,
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

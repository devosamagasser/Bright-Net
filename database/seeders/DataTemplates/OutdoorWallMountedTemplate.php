<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class OutdoorWallMountedTemplate
{
    public function build(int $subcategoryId): void
    {
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor Wall Mounted Light Datasheet Template',
                'description' => 'Specification template for outdoor wall-mounted luminaires',
            ],
            'ar' => [
                'name' => 'قالب بيانات إضاءة جدارية خارجية',
                'description' => 'قالب المواصفات الفنية لوحدات الإضاءة الجدارية الخارجية',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Mounting / Light Direction
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'mounting_style',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Up Light',
                'Down Light',
                'Up & Down',
                'Bulkhead',
                'Decorative Sconce',
            ],
            'en' => ['label' => 'Mounting Style'],
            'ar' => ['label' => 'أسلوب التركيب / اتجاه الإضاءة'],
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
                'Polycarbonate (PC)',
                'Stainless Steel (304)',
                'Stainless Steel (316)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Diffuser / Cover Type
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'diffuser_type',
            'position' => 3,
            'is_required' => true,
            'options' => [
                'Clear Glass',
                'Frosted Glass',
                'Opal PC',
                'Prismatic PC',
            ],
            'en' => ['label' => 'Diffuser Type'],
            'ar' => ['label' => 'نوع الناشر / الغطاء'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions (Wall Plate / Fixture Size)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 4,
            'is_required' => false,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 5,
            'is_required' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'depth_mm',
            'position' => 6,
            'is_required' => false,
            'en' => ['label' => 'Depth / Projection (mm)'],
            'ar' => ['label' => 'العمق / البروز عن الحائط (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Beam Type (Up/Down / Decorative)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_type',
            'position' => 7,
            'is_required' => true,
            'options' => [
                'Wide Flood',
                'Narrow Beam',
                'Up/Down Blade',
                'Asymmetric',
                'Decorative Glow',
            ],
            'en' => ['label' => 'Beam Type'],
            'ar' => ['label' => 'نوع الشعاع'],
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
            'is_filterable' => true,
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
            'is_filterable' => true,
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
            'is_filterable' => true,
            'options' => [
                '2700K',
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
        | 9 — CRI
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 11,
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
        | 10 — Input Voltage
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 12,
            'is_required' => true,
            'options' => [
                '220-240V AC',
                '100-277V AC',
                '24V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
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
            'is_filterable' => true,
            'options' => [
                'IP54',
                'IP65',
                'IP66',
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
            'ar' => ['label' => 'مقاومة الصدمات'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13 — Warranty
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 15,
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

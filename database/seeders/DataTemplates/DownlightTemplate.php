<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class DownlightTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Downlight Datasheet Template',
                'description' => 'Technical specification template for indoor downlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات الداونية',
                'description' => 'قالب المواصفات الفنية لكشافات الداون لايت'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 1 — INSTALLATION TYPE
        |--------------------------------------------------------------------------
        */
        $installation = $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Recessed (Trimmed)',
                'Recessed (Trimless / Plaster-in)',
            ],
            'en' => ['label' => 'Installation Type', 'placeholder' => 'Select mounting type'],
            'ar' => ['label' => 'طريقة التثبيت', 'placeholder' => 'اختر طريقة التركيب'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 2 — HOUSING MATERIAL
        |--------------------------------------------------------------------------
        */
        $housing = $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 2,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
                'Polycarbonate (PC)',
                'ABS',
                'PMMA (Acrylic)',
                'Stainless Steel (304)',
            ],
            'en' => ['label' => 'Housing Material', 'placeholder' => 'Select material'],
            'ar' => ['label' => 'مادة الهيكل', 'placeholder' => 'اختر مادة التصنيع'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 3 — SHAPE
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 3,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['Round', 'Square'],
            'en' => ['label' => 'Shape', 'placeholder' => 'Choose shape'],
            'ar' => ['label' => 'الشكل', 'placeholder' => 'اختر الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 4 — DIAMETER (depends on Round)
        |--------------------------------------------------------------------------
        */
        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Diameter (mm)', 'placeholder' => 'Enter diameter'],
            'ar' => ['label' => 'القطر (مم)', 'placeholder' => 'ادخل القطر'],
        ]);

        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 5 — SIDE LENGTH (depends on Square)
        |--------------------------------------------------------------------------
        */
        $side = $template->fields()->create([
            'type' => 'number',
            'name' => 'side',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Side Length (mm)', 'placeholder' => 'Enter side length'],
            'ar' => ['label' => 'طول الضلع (مم)', 'placeholder' => 'ادخل طول الضلع'],
        ]);

        $side->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Square'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 6 — CUTOUT (depends on shape)
        |--------------------------------------------------------------------------
        */
        $cutout = $template->fields()->create([
            'type' => 'number',
            'name' => 'cutout',
            'position' => 6,
            'is_required' => true,
            'is_filterable' => false,
            'en' => ['label' => 'Cutout (mm)', 'placeholder' => 'Enter cutout dimension'],
            'ar' => ['label' => 'فتحة التركيب (مم)', 'placeholder' => 'ادخل مقاس الفتحة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 7 — INPUT POWER
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'position' => 7,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Power (W)', 'placeholder' => 'Enter power'],
            'ar' => ['label' => 'القدرة (واط)', 'placeholder' => 'ادخل القدرة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 8 — VOLTAGE
        |--------------------------------------------------------------------------
        */
        $voltage = $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 8,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['220-240V AC', '100-277V AC'],
            'en' => ['label' => 'Input Voltage', 'placeholder' => 'Select voltage'],
            'ar' => ['label' => 'جهد التشغيل', 'placeholder' => 'اختر الجهد'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 9 — CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 9,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['2700K','3000K','3500K','4000K','5000K'],
            'en' => ['label' => 'CCT', 'placeholder' => 'Select CCT'],
            'ar' => ['label' => 'درجة حرارة اللون', 'placeholder' => 'اختر CCT'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 10 — BEAM ANGLE
        |--------------------------------------------------------------------------
        */
        $beam = $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['<18°','18°-29°','29°-46°','46°-70°'],
            'en' => ['label' => 'Beam Angle', 'placeholder' => 'Select beam angle'],
            'ar' => ['label' => 'زاوية الإضاءة', 'placeholder' => 'اختر زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 11 — CRI
        |--------------------------------------------------------------------------
        */
        $cri = $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 11,
            'is_required' => false,
            'is_filterable' => true,
            'options' => ['CRI 80+','CRI 90+'],
            'en' => ['label' => 'CRI', 'placeholder' => 'Select CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون', 'placeholder' => 'اختر CRI'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 12 — UGR
        |--------------------------------------------------------------------------
        */
        $ugr = $template->fields()->create([
            'type' => 'select',
            'name' => 'ugr',
            'position' => 12,
            'is_required' => false,
            'is_filterable' => false,
            'options' => ['UGR<16','UGR<19','UGR<22'],
            'en' => ['label' => 'UGR Rating', 'placeholder' => 'Select UGR'],
            'ar' => ['label' => 'تصنيف الوهج', 'placeholder' => 'اختر UGR'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 13 — IP RATING
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20','IP40','IP44'],
            'en' => ['label' => 'IP Rating', 'placeholder' => 'Select IP'],
            'ar' => ['label' => 'تصنيف الحماية', 'placeholder' => 'اختر IP'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 14 — WARRANTY
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 14,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['2 Years','3 Years','5 Years'],
            'en' => ['label' => 'Warranty', 'placeholder' => 'Select warranty'],
            'ar' => ['label' => 'الضمان', 'placeholder' => 'اختر الضمان'],
        ]);
    }
}

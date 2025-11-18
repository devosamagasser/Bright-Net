<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\Models\DataField;

class SpotlightTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Spotlight Datasheet Template',
                'description' => 'Specification template for indoor spotlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات سبوت لايت',
                'description' => 'قالب المواصفات الفنية لكشافات السبوت لايت الداخلية'
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
                'Surface Mounted',
                'Recessed (Trimmed)',
                'Recessed (Trimless)',
                'Track Mounted (3-Phase)',
                'Track Mounted (Magnetic)',
            ],
            'en' => ['label' => 'Installation Type', 'placeholder' => 'Select installation'],
            'ar' => ['label' => 'نوع التثبيت', 'placeholder' => 'اختر نوع التركيب'],
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
                'PMMA (Acrylic)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
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
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 4 — DIAMETER (Round)
        |--------------------------------------------------------------------------
        */
        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter',
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

        /*
        |--------------------------------------------------------------------------
        | FIELD 5 — SIDE LENGTH (Square)
        |--------------------------------------------------------------------------
        */
        $side = $template->fields()->create([
            'type' => 'number',
            'name' => 'side_length',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Side Length (mm)'],
            'ar' => ['label' => 'طول الضلع (مم)'],
        ]);

        $side->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Square'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 6 — TILT ANGLE
        |--------------------------------------------------------------------------
        */
        $tilt = $template->fields()->create([
            'type' => 'number',
            'name' => 'tilt_angle',
            'position' => 6,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Tilt Angle (°)'],
            'ar' => ['label' => 'زاوية الميلان (°)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 7 — ROTATION ANGLE
        |--------------------------------------------------------------------------
        */
        $rotation = $template->fields()->create([
            'type' => 'number',
            'name' => 'rotation_angle',
            'position' => 7,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Rotation Angle (°)'],
            'ar' => ['label' => 'زاوية الدوران (°)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 8 — BEAM ANGLE
        |--------------------------------------------------------------------------
        */
        $beam = $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 8,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '<18° (Very Narrow)',
                '18°-29° (Narrow)',
                '29°-46° (Spot)',
                '46°-70° (Flood)',
                '70°-100° (Wide Flood)',
                '>100° (Very Wide)',
                'Asymmetric',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 9 — OPTIC TYPE
        |--------------------------------------------------------------------------
        */
        $optic = $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 9,
            'is_required' => false,
            'is_filterable' => true,
            'options' => [
                'Lens (Spot)',
                'Lens (Flood)',
                'Asymmetric Lens',
                'Reflector (Baffle)',
                'Clear Cover',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 10 — POWER
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'en' => ['label' => 'Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 11 — VOLTAGE
        |--------------------------------------------------------------------------
        */
        $voltage = $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 11,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['220-240V AC', '100-277V AC'],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 12 — CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['2700K','3000K','3500K','4000K','5000K','5700K'],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 13 — CRI
        |--------------------------------------------------------------------------
        */
        $cri = $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['CRI 80+', 'CRI 90+', 'CRI 95+'],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 14 — IP RATING
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 14,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20','IP40','IP44'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 15 — WARRANTY
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 15,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['2 Years','3 Years','5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

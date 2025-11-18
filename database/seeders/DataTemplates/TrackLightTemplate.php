<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\Models\DataField;

class TrackLightTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Track Light Datasheet Template',
                'description' => 'Technical template for indoor track-mounted spotlights'
            ],
            'ar' => [
                'name' => 'قالب بيانات تراك لايت',
                'description' => 'قالب المواصفات الفنية لكشافات التراك لايت الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 1 — TRACK SYSTEM TYPE
        |--------------------------------------------------------------------------
        */
        $trackSystem = $template->fields()->create([
            'type' => 'select',
            'name' => 'track_system',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '3-Circuit (Global Pro)',
                '1-Circuit (Basic Track)',
                'DALI Track',
                'Magnetic Track',
                'Low Voltage (48V)',
            ],
            'en' => ['label' => 'Track System', 'placeholder' => 'Select system'],
            'ar' => ['label' => 'نظام التراك', 'placeholder' => 'اختر نظام التراك'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 2 — ADAPTER TYPE (depends on system)
        |--------------------------------------------------------------------------
        */
        $adapter = $template->fields()->create([
            'type' => 'select',
            'name' => 'adapter_type',
            'position' => 2,
            'is_required' => true,
            'is_filterable' => false,
            'options' => [
                'Global Trac 3-Phase Adapter',
                '1-Phase Track Adapter',
                'DALI Track Adapter',
                'Magnetic Track Module',
                '48V Track Adapter',
            ],
            'en' => ['label' => 'Adapter Type'],
            'ar' => ['label' => 'نوع الأداپتر'],
        ]);

        $adapter->dependency()->create([
            'depends_on_field_id' => $trackSystem->id,
            'values' => [
                '3-Circuit (Global Pro)',
                '1-Circuit (Basic Track)',
                'DALI Track',
                'Magnetic Track',
                'Low Voltage (48V)',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 3 — HOUSING MATERIAL
        |--------------------------------------------------------------------------
        */
        $housing = $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 3,
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
        | FIELD 4 — TILT ANGLE
        |--------------------------------------------------------------------------
        */
        $tilt = $template->fields()->create([
            'type' => 'number',
            'name' => 'tilt_angle',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Tilt Angle (°)'],
            'ar' => ['label' => 'زاوية الميلان (°)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 5 — ROTATION ANGLE
        |--------------------------------------------------------------------------
        */
        $rotation = $template->fields()->create([
            'type' => 'number',
            'name' => 'rotation_angle',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => false,
            'en' => ['label' => 'Rotation Angle (°)'],
            'ar' => ['label' => 'زاوية الدوران (°)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 6 — OPTIC TYPE
        |--------------------------------------------------------------------------
        */
        $optic = $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 6,
            'is_required' => false,
            'is_filterable' => true,
            'options' => [
                'Lens (Spot)',
                'Lens (Flood)',
                'Asymmetric Lens',
                'Reflector (Baffle)',
                'Darklight Reflector',
                'Clear Cover',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 7 — BEAM ANGLE
        |--------------------------------------------------------------------------
        */
        $beam = $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 7,
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
        | FIELD 8 — INPUT POWER
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
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
        | FIELD 9 — INPUT VOLTAGE
        |--------------------------------------------------------------------------
        */
        $voltage = $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 9,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['220-240V AC', '100-277V AC', '48V DC'],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 10 — CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 10,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '2700K', '3000K', '3500K', '4000K',
                '5000K', '5700K',
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
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
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'CRI 80+',
                'CRI 90+',
                'CRI 95+'
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 12 — IP RATING
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 12,
            'is_required' => true,
            'is_filterable' => true,
            'options' => ['IP20','IP40','IP44'],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD 13 — WARRANTY
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 13,
            'is_required' => true,
            'is_filterable' => false,
            'options' => ['2 Years','3 Years','5 Years'],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

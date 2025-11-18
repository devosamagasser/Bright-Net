<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class WallGrazerTemplate
{
    public function build(int $subcategoryId): void
    {
        // Create Template
        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Wall Grazer Datasheet Template',
                'description' => 'Specification template for architectural wall-grazer linear lighting'
            ],
            'ar' => [
                'name' => 'قالب بيانات وول جريزر',
                'description' => 'قالب المواصفات الفنية لإضاءة الوول جريزر المعمارية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1 — Installation Type (Grazer-Specific)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 1,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Recessed (Linear)',
                'Surface Mounted (Close to wall)',
                'Pendant Mounted (Graze effect)',
                'Track Mounted (Grazer type)',
                'Wall Mounted (Slim profile)',
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
                'Aluminum (Extruded)',
                'Aluminum (Die-cast)',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3 — Shape (Always Linear)
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 3,
            'is_required' => true,
            'options' => ['Linear'],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4 — Dimensions (Length / Width / Height)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'number',
            'name' => 'length_mm',
            'position' => 4,
            'is_required' => false,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'width_mm',
            'position' => 5,
            'is_required' => false,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);

        $template->fields()->create([
            'type' => 'number',
            'name' => 'height_mm',
            'position' => 6,
            'is_required' => false,
            'en' => ['label' => 'Height (mm)'],
            'ar' => ['label' => 'الارتفاع (مم)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5 — Optic Type (Ultra Narrow – Grazer Specific)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'optic_type',
            'position' => 7,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                'Ultra-Narrow Lens',
                'Asymmetric Grazer Lens',
                'Blade Light Lens',
                'Linear Lens',
            ],
            'en' => ['label' => 'Optic Type'],
            'ar' => ['label' => 'نوع العدسة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6 — Beam Angle (Critical for Texture Highlighting)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'beam_angle',
            'position' => 8,
            'is_required' => true,
            'is_filterable' => true,
            'options' => [
                '5°',
                '10°',
                '15°',
                '20°',
                'Asymmetric Grazer',
                'Wall Texture Mode',
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7 — Power (Lower than Washer)
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
        | 8 — Luminous Flux
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
        | 9 — CCT
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 11,
            'is_required' => true,
            'options' => [
                '2700K',
                '3000K',
                '3500K',
                '4000K',
                '5000K',
                'RGB',
                'RGBW',
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10 — CRI (Grazer Needs High CRI)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 12,
            'is_required' => true,
            'options' => [
                'CRI 80+',
                'CRI 90+',
                'CRI 95+',
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11 — Input Voltage (Mostly Low Voltage)
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 13,
            'is_required' => true,
            'options' => [
                '24V DC',
                '48V DC',
                '220-240V AC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12 — IP Rating
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 14,
            'is_required' => true,
            'options' => [
                'IP20',
                'IP40',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'تصنيف الحماية'],
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
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

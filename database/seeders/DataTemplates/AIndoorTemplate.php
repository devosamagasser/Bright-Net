<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class AIndoorTemplate
{
    public function build(int $subcategoryId)
    {

        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Indoor Datasheet Template',
                'description' => 'Specification template for indoor lighting products'
            ],
            'ar' => [
                'name' => 'قالب بيانات داخلي',
                'description' => 'قالب المواصفات الفنية لمنتجات الإضاءة الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — RECOMMENDED APPLICATIONS
        |--------------------------------------------------------------------------
        */
        $applications = $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'recommended_applications',
            'position' => 1,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'Office & Corporate',
                'Retail & Showroom',
                'Hospitality (Hotels, Restaurants)',
                'Residential & Living',
                'Culture (Museums, Galleries)',
                'Education (Schools, Libraries)',
                'Healthcare',
                'Industrial & Logistics',
                'Sports (Indoor)',
                'Food & Beverage Processing',
                'Pharmaceutical / Cleanroom',
            ],
            'en' => ['label' => 'Recommended Applications'],
            'ar' => ['label' => 'الاستخدامات الموصى بها'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — INSTALLATION TYPE
        |--------------------------------------------------------------------------
        */
        $installation = $template->fields()->create([
            'type' => 'select',
            'name' => 'installation_type',
            'position' => 2,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'Recessed (Trimmed)',
                'Recessed (Trimless / Plaster-in)',
                'Surface Mounted',
                'Suspended (Pendant - from ceiling)',
                'Suspended (Catenary - on horizontal wire)',
                'Track (3-Phase / LVM)',
                'Track (Magnetic)',
                'Wall Mounted (Surface)',
                'Wall Mounted (Recessed)',
                'Floor Mounted',
                'In-Ground',
                'Pole Mounted',
                'Bollard',
            ],
            'en' => ['label' => 'Installation Type'],
            'ar' => ['label' => 'نوع التركيب'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — HOUSING MATERIAL
        |--------------------------------------------------------------------------
        */
        $housing = $template->fields()->create([
            'type' => 'select',
            'name' => 'housing_material',
            'position' => 3,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'Aluminum (Die-cast)',
                'Aluminum (Extruded)',
                'Polycarbonate (PC)',
                'ABS (Acrylonitrile Butadiene Styrene)',
                'PMMA (Acrylic)',
                'GRP (Glass-Reinforced Plastic)',
                'Stainless Steel (304)',
                'Stainless Steel (316 / Marine Grade)',
                'Glass',
                'Gypsum / Plaster',
            ],
            'en' => ['label' => 'Housing Material'],
            'ar' => ['label' => 'مادة الهيكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — FINISH COLOR
        |--------------------------------------------------------------------------
        */
        $finish = $template->fields()->create([
            'type' => 'groupedselect',
            'name' => 'finish_color',
            'position' => 4,
            'is_required' => false,
            'is_filterable' => false,
            'with_custom' => true,
            'options' => [
                'Standard' => [
                    'White (Matte)',
                    'White (Gloss)',
                    'Black (Matte)',
                    'Black (Gloss)',
                    'Grey / Silver',
                    'Graphite / Anthracite',
                ],
                'Metallic' => [
                    'Anodized Aluminum',
                    'Brushed Aluminum',
                    'Chrome (Polished)',
                    'Bronze',
                    'Brass',
                    'Copper',
                    'Custom RAL',
                ],
            ],
            'en' => ['label' => 'Finish Color'],
            'ar' => ['label' => 'لون التشطيب'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — DIFFUSER / OPTIC TYPE
        |--------------------------------------------------------------------------
        */
        $diffuser = $template->fields()->create([
            'type' => 'select',
            'name' => 'diffuser_optic_type',
            'position' => 16,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'Opal / Frosted (Uniform light)',
                'Microprismatic (Low Glare - UGR<19)',
                'Baffle / Darklight (Deep recessed)',
                'Louvre (Grid cover)',
                'Lens (Clear/Spot)',
                'Asymmetric Lens (Wall-wash)',
                'Clear Cover (Protective)',
            ],
            'en' => [
                'label' => 'Diffuser / Optic Type',
                'placeholder' => 'Select optic type',
            ],
            'ar' => [
                'label' => 'نوع الناشر / العدسة',
                'placeholder' => 'اختر نوع التحكم في الإضاءة',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — SHAPE
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'name' => 'shape',
            'position' => 5,
            'is_required' => false,
            'is_filterable' => false,
            'options' => ['Linear', 'Rectangular', 'Square', 'Round'],
            'en' => ['label' => 'Shape'],
            'ar' => ['label' => 'الشكل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DIMENSIONS — CONDITIONAL
        |--------------------------------------------------------------------------
        */
        $length = $template->fields()->create([
            'type' => 'number',
            'name' => 'length',
            'position' => 6,
            'en' => ['label' => 'Length (mm)'],
            'ar' => ['label' => 'الطول (مم)'],
        ]);
        $length->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Linear', 'Rectangular'],
        ]);

        $width = $template->fields()->create([
            'type' => 'number',
            'name' => 'width',
            'position' => 7,
            'en' => ['label' => 'Width (mm)'],
            'ar' => ['label' => 'العرض (مم)'],
        ]);
        $width->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Linear', 'Rectangular'],
        ]);

        $depth = $template->fields()->create([
            'type' => 'number',
            'name' => 'depth',
            'position' => 7,
            'en' => ['label' => 'Depth (mm)'],
            'ar' => ['label' => 'العمق (مم)'],
        ]);
        $depth->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Linear', 'Rectangular', 'square', 'Round'],
        ]);

        $side = $template->fields()->create([
            'type' => 'number',
            'name' => 'side',
            'position' => 8,
            'en' => ['label' => 'Side (mm)'],
            'ar' => ['label' => 'الضلع (مم)'],
        ]);
        $side->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Square'],
        ]);

        $diameter = $template->fields()->create([
            'type' => 'number',
            'name' => 'diameter',
            'position' => 9,
            'en' => ['label' => 'Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);
        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ELECTRICAL
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
            'type' => 'number',
            'name' => 'input_power',
            'suffix' => 'W',
            'position' => 10,
            'is_required' => false,
            'is_filterable' => true,
            'en' => ['label' => 'Input Power (W)'],
            'ar' => ['label' => 'القدرة (واط)'],
        ]);

        $voltage = $template->fields()->create([
            'type' => 'select',
            'name' => 'input_voltage',
            'position' => 11,
            'is_filterable' => false,
            'options' => [
                '220-240V AC',
                '100-277V AC',
                '12V DC',
                '24V DC',
                '48V DC',
            ],
            'en' => ['label' => 'Input Voltage'],
            'ar' => ['label' => 'جهد التشغيل'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DRIVER CONTROL
        |--------------------------------------------------------------------------
        */
        $driver = $template->fields()->create([
            'type' => 'select',
            'name' => 'driver_control',
            'position' => 12,
            'options' => [
                'Non-Dimmable (On/Off)',
                'TRIAC',
                '1-10V',
                '0-10V',
                'DALI',
                'DMX',
                'Wireless',
                'Push-button',
            ],
            'en' => ['label' => 'Driver Control'],
            'ar' => ['label' => 'نظام التحكم'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LIGHT SOURCE TYPE
        |--------------------------------------------------------------------------
        */
        $lightSource = $template->fields()->create([
            'type' => 'select',
            'name' => 'light_source_type',
            'position' => 17,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                'Integrated LED (Chip is built-in)',
                'Replaceable Lamp (Fixture is a holder/socket)',
            ],
            'en' => ['label' => 'Light Source Type'],
            'ar' => ['label' => 'نوع مصدر الضوء'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LED TYPE (Conditional)
        |--------------------------------------------------------------------------
        */
        $ledType = $template->fields()->create([
            'type' => 'select',
            'name' => 'led_type',
            'position' => 18,
            'options' => [
                'SMD (Surface Mount Device - e.g., 2835, 5050)',
                'COB (Chip on Board - Single large chip)',
                'MCOB (Multi-Chip on Board)',
                'Filament (LED)',
            ],
            'en' => ['label' => 'LED Type'],
            'ar' => ['label' => 'نوع الـ LED'],
        ]);

        $ledType->dependency()->create([
            'depends_on_field_id' => $lightSource->id,
            'values' => ['Integrated LED (Chip is built-in)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LAMP BASE / SOCKET (Conditional)
        |--------------------------------------------------------------------------
        */
        $lampBase = $template->fields()->create([
            'type' => 'select',
            'name' => 'lamp_base',
            'position' => 19,
            'options' => [
                'E27 (Edison Screw)',
                'E14 (Small Edison Screw)',
                'GU10 (Twist-Lock)',
                'MR16 / GU5.3 (Low Voltage Pins)',
                'G9 (Bi-pin)',
                'G4 (Bi-pin)',
                'T8 / G13 (Linear Tube)',
                'T5 / G5 (Linear Tube)',
                'AR111 / G53',
                'GX53',
            ],
            'en' => ['label' => 'Lamp Base / Socket'],
            'ar' => ['label' => 'قاعدة اللمبة'],
        ]);

        $lampBase->dependency()->create([
            'depends_on_field_id' => $lightSource->id,
            'values' => ['Replaceable Lamp (Fixture is a holder/socket)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LAMP INCLUDED (Conditional)
        |--------------------------------------------------------------------------
        */
        $lampIncluded = $template->fields()->create([
            'type' => 'select',
            'name' => 'lamp_included',
            'position' => 20,
            'options' => ['Yes', 'No'],
            'en' => ['label' => 'Lamp Included'],
            'ar' => ['label' => 'هل اللمبة مرفقة؟'],
        ]);

        $lampIncluded->dependency()->create([
            'depends_on_field_id' => $lightSource->id,
            'values' => ['Replaceable Lamp (Fixture is a holder/socket)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LAMP WATTAGE MAX (Conditional)
        |--------------------------------------------------------------------------
        */
        $lampWattage = $template->fields()->create([
            'type' => 'number',
            'name' => 'lamp_wattage_max',
            'position' => 21,
            'en' => ['label' => 'Lamp Wattage (Max) W'],
            'ar' => ['label' => 'أقصى قدرة لللمبة (واط)'],
        ]);

        $lampWattage->dependency()->create([
            'depends_on_field_id' => $lightSource->id,
            'values' => ['Replaceable Lamp (Fixture is a holder/socket)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PHOTOMETRIC — LUMINOUS FLUX
        |--------------------------------------------------------------------------
        */
        $lumen = $template->fields()->create([
            'type' => 'number',
            'name' => 'luminous_flux',
            'position' => 22,
            'is_required' => false,
            'is_filterable' => true,
            'en' => ['label' => 'Luminous Flux (lm)'],
            'ar' => ['label' => 'شدة الإضاءة (لومن)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'name' => 'cct',
            'position' => 23,
            'is_required' => false,
            'is_filterable' => true,
            'options' => [
                [
                    'label' => '2700K (Very Warm White - Incandescent)',
                    'value' => '2700K',
                ],
                [
                    'label' => '3000K (Warm White - Hospitality/Home)',
                    'value' => '3000K',
                ],
                [
                    'label' => '3500K (Neutral-Warm White - Commercial)',
                    'value' => '3500K',
                ],
                [
                    'label' => '4000K (Neutral White - Office/Retail)',
                    'value' => '4000K',
                ],
                [
                    'label' => '5000K (Cool White - Industrial/Task)',
                    'value' => '5000K',
                ],
                [
                    'label' => '5700K (Daylight - High Task/Outdoor)',
                    'value' => '5700K',
                ],
                [
                    'label' => '6000K (Daylight - Industrial)',
                    'value' => '6000K',
                ],
                [
                    'label' => '6500K (Very Cool Daylight)',
                    'value' => '6500K',
                ],
                [
                    'label' => 'Tunable White (Adjustable 2700K-6500K)',
                    'value' => 'Tunable White',
                ],
                [
                    'label' => 'RGB (Color Changing)',
                    'value' => 'RGB',
                ],
                [
                    'label' => 'RGBW (Color + White)',
                    'value' => 'RGBW',
                ]
            ],
            'en' => ['label' => 'CCT'],
            'ar' => ['label' => 'درجة حرارة اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CRI
        |--------------------------------------------------------------------------
        */
        $cri = $template->fields()->create([
            'type' => 'select',
            'name' => 'cri',
            'position' => 24,
            'options' => [
                ['label' => 'CRI 70+ (Functional)', 'value' => 'CRI 70+'],
                ['label' => 'CRI 80+ (Standard)', 'value' => 'CRI 80+'],
                ['label' => 'CRI 90+ (High-End)', 'value' => 'CRI 90+'],
                ['label' => 'CRI 95+ (Retail / Art)', 'value' => 'CRI 95+'],
                ['label' => 'CRI 98+ (Museum Grade)', 'value' => 'CRI 98+']
            ],
            'en' => ['label' => 'CRI'],
            'ar' => ['label' => 'معامل تجسيد اللون'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | BEAM ANGLE
        |--------------------------------------------------------------------------
        */
        $beam = $template->fields()->create([
            'type' => 'groupedselect',
            'name' => 'beam_angle',
            'position' => 25,
            'with_custom' => true,
            'options' => [
                'Standard Spots' => [
                    '< 18°',
                    '18°-29°',
                    '29°-46°',
                ],
                'Standard Floods' => [
                    '46°-70°',
                    '70°-100°',
                    '> 100°',
                ],
                'Specialist' => [
                    'Asymmetric',
                    'Double Asymmetric',
                ],
            ],
            'en' => ['label' => 'Beam Angle'],
            'ar' => ['label' => 'زاوية الإضاءة'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | UGR
        |--------------------------------------------------------------------------
        */
        $ugr = $template->fields()->create([
            'type' => 'select',
            'name' => 'ugr',
            'position' => 26,
            'options' => [
                'UGR <16',
                'UGR <19',
                'UGR <22',
                'UGR <25',
                'UGR >28',
            ],
            'en' => ['label' => 'UGR'],
            'ar' => ['label' => 'تصنيف الوهج'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SDCM
        |--------------------------------------------------------------------------
        */
        $sdcm = $template->fields()->create([
            'type' => 'select',
            'name' => 'sdcm',
            'position' => 27,
            'options' => [
                '1-Step','2-Step','3-Step','5-Step','7-Step',
            ],
            'en' => ['label' => 'MacAdam Ellipse (SDCM)'],
            'ar' => ['label' => 'ثبات اللون (SDCM)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LUMEN MAINTENANCE (L-B)
        |--------------------------------------------------------------------------
        */
        $l_value = $template->fields()->create([
            'type' => 'select',
            'name' => 'l_value',
            'position' => 28,
            'options' => ['L50','L60','L70','L80','L90'],
            'en' => ['label' => 'L-Value'],
            'ar' => ['label' => 'قيمة L'],
        ]);

        $b_value = $template->fields()->create([
            'type' => 'select',
            'name' => 'b_value',
            'position' => 29,
            'options' => ['B05','B10','B20','B50'],
            'en' => ['label' => 'B-Value'],
            'ar' => ['label' => 'قيمة B'],
        ]);

        $l_hours = $template->fields()->create([
            'type' => 'select',
            'name' => 'lifetime_hours',
            'position' => 30,
            'options' => [
                '15000','25000','30000','35000',
                '50000','60000','75000','100000',
            ],
            'en' => ['label' => 'Lifetime (Hours)'],
            'ar' => ['label' => 'العمر التشغيلي (ساعات)'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | IP RATING
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'name' => 'ip_rating',
            'position' => 31,
            'is_filterable' => false,
            'options' => [
                'IP20','IP40','IP44','IP54','IP65',
                'IP66','IP67','IP68','IP69K',
            ],
            'en' => ['label' => 'IP Rating'],
            'ar' => ['label' => 'درجة الحماية IP'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | IK RATING
        |--------------------------------------------------------------------------
        */
        $ik = $template->fields()->create([
            'type' => 'select',
            'name' => 'ik_rating',
            'position' => 32,
            'options' => [
                'IK00','IK02','IK05','IK07','IK08','IK10',
            ],
            'en' => ['label' => 'IK Rating'],
            'ar' => ['label' => 'مقاومة الصدمات IK'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | WARRANTY
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'name' => 'warranty',
            'position' => 33,
            'options' => [
                '1 Year','2 Years','3 Years',
                '5 Years','7 Years','10 Years',
            ],
            'en' => ['label' => 'Warranty'],
            'ar' => ['label' => 'الضمان'],
        ]);
    }
}

<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class AGeneralTemplate
{
    public function build($template)
    {

        /*
        |--------------------------------------------------------------------------
        | FIELD — INSTALLATION TYPE
        |--------------------------------------------------------------------------
        */
        $installation = $template->fields()->create([
            'type' => 'select',
            'group' => 'Physical',
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
            'group' => 'Physical',
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
        $template->fields()->create([
            'type' => 'groupedselect',
            'group' => 'Physical',
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
                ],
            ],
            'en' => [
                'label' => 'Finish Color',
                'placeholder' => 'Select or Enter Custom RAL',
            ],
            'ar' => [
                'label' => 'لون التشطيب',
                'placeholder' => 'اختر اللون أو حدد لوناً مخصصاً',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — DIFFUSER / OPTIC TYPE
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'select',
            'group' => 'Physical',
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
        | BEAM ANGLE
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'groupedselect',
            'name' => 'beam_angle',
            'position' => 25,
            'with_custom' => true,
            'group' => 'Physical',
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
            'en' => [
                'label' => 'Beam Angle',
                'placeholder' => 'Select or type custom angle',
            ],
            'ar' => [
                'label' => 'زاوية الإضاءة',
                'placeholder' => 'اختر الزاوية أو حدد زاوية مخصصة',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — SHAPE
        |--------------------------------------------------------------------------
        */
        $shape = $template->fields()->create([
            'type' => 'select',
            'group' => 'Physical',
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
            'group' => 'Physical',
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
            'group' => 'Physical',
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
            'group' => 'Physical',
            'name' => 'depth',
            'position' => 7,
            'en' => ['label' => 'Depth (mm)'],
            'ar' => ['label' => 'العمق (مم)'],
        ]);
        $depth->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Linear', 'Rectangular', 'Square', 'Round'],
        ]);

        $side = $template->fields()->create([
            'type' => 'number',
            'group' => 'Physical',
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
            'group' => 'Physical',
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
        | CutOut DIMENSIONS — CONDITIONAL
        |--------------------------------------------------------------------------
        */
        $cutoutLength = $template->fields()->create([
            'type' => 'number',
            'group' => 'Physical',
            'name' => 'cutout_length',
            'position' => 6,
            'en' => ['label' => 'Cutout Length (mm)', 'placeholder' => 'Enter length in millimeters'],
            'ar' => ['label' => 'الطول (مم)', 'placeholder' => 'أدخل الطول بالملليمترات'],
        ]);
        $cutoutLength->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Rectangular', 'Linear'],
        ]);

        $cutoutWidth = $template->fields()->create([
            'type' => 'number',
            'group' => 'Physical',
            'name' => 'cutout_width',
            'position' => 7,
            'en' => ['label' => 'Cutout Width (mm)', 'placeholder' => 'Enter width in millimeters'],
            'ar' => ['label' => 'العرض (مم)', 'placeholder' => 'أدخل العرض بالملليمترات'],
        ]);
        $cutoutWidth->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Rectangular', 'Linear'],
        ]);

        $side = $template->fields()->create([
            'type' => 'number',
            'group' => 'Physical',
            'name' => 'cutout_side',
            'position' => 8,
            'en' => ['label' => 'Cutout Side (mm)'],
            'ar' => ['label' => 'الضلع (مم)'],
        ]);
        $side->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Square'],
        ]);

        $diameter = $template->fields()->create([
            'type' => 'number',
            'group' => 'Physical',
            'name' => 'cutout_diameter',
            'position' => 9,
            'en' => ['label' => 'Cutout Diameter (mm)'],
            'ar' => ['label' => 'القطر (مم)'],
        ]);
        $diameter->dependency()->create([
            'depends_on_field_id' => $shape->id,
            'values' => ['Round'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LIGHT SOURCE TYPE
        |--------------------------------------------------------------------------
        */
        $lightSource = $template->fields()->create([
            'type' => 'select',
            'group' => 'Photometric',
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
            'group' => 'Photometric',
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
            'group' => 'Photometric',
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
            'group' => 'Photometric',
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
        | PHOTOMETRIC — LUMINOUS FLUX
        |--------------------------------------------------------------------------
        */
        $lumen = $template->fields()->create([
            'type' => 'number',
            'group' => 'Photometric',
            'name' => 'luminous_flux',
            'position' => 22,
            'is_required' => false,
            'is_filterable' => true,
            'en' => [
                'label' => 'Luminous Flux (lm)',
                'placeholder' => 'Total light output in lumens',
            ],
            'ar' => [
                'label' => 'شدة الإضاءة (لومن)',
                'placeholder' => 'إجمالي كمية الضوء باللومن',
            ],
        ]);

                /*
        |--------------------------------------------------------------------------
        | LUMEN MAINTENANCE (L-B)
        |--------------------------------------------------------------------------
        */
        $l_value = $template->fields()->create([
            'type' => 'select',
            'group' => 'Photometric',
            'name' => 'l_value',
            'position' => 28,
            'options' => [
                [
                    'label' => 'L50 (Maintains 50%)',
                    'value' => 'L50'
                ],
                [
                    'label' => 'L60 (Maintains 60%)',
                    'value' => 'L60'
                ],
                [
                    'label' => 'L70 (Maintains 70%)',
                    'value' => 'L70'
                ],
                [
                    'label' => 'L80 (Maintains 80%)',
                    'value' => 'L80'
                ],
                [
                    'label' => 'L90 (Maintains 90%)',
                    'value' => 'L90'
                ]
            ],
            'en' => [
                'label' => 'L-Value',
                'placeholder' => '% of initial brightness maintained'
            ],
            'ar' => [
                'label' => 'قيمة L',
                'placeholder' => '% of initial brightness maintained'
            ],
        ]);

        $b_value = $template->fields()->create([
            'type' => 'select',
            'group' => 'Photometric',
            'name' => 'b_value',
            'position' => 29,
            'options' => [
                [
                    'label' => 'B05 (5% fail L-value - Premium)',
                    'value'=>'B050',
                ],
                [
                    'label' => 'B10 (10% fail L-value - Standard)',
                    'value'=>'B10',
                ],
                [
                    'label' => 'B20 (20% fail L-value - Economy)',
                    'value'=>'B20',
                ],
                [
                    'label' => 'B50 (50% fail L-value - Consumer)',
                    'value'=>'B50',
                ],
            ],
            'en' => [
                'label' => 'B-Value',
                'placeholder' => '% of products that fail to meet L-Value'
            ],

            'ar' => [
                'label' => 'قيمة B',
                'placeholder' => '% of products that fail to meet L-Value'
            ],

        ]);

        $l_hours = $template->fields()->create([
            'type' => 'select',
            'group' => 'Photometric',
            'name' => 'lifetime_hours',
            'position' => 30,
            'options' => [
                '15000 hrs','25000 hrs','30000 hrs','35000 hrs',
                '50000 hrs','60000 hrs','75000 hrs','100000 hrs',
            ],
            'en' => [
                'label' => 'Lifetime (Hours (at L-B))',
                'placeholder' => 'Rated operational life'
            ],
            'ar' => [
                'label' => 'العمر التشغيلي (ساعات)',
                'placeholder' => 'Rated operational life'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ELECTRICAL
        |--------------------------------------------------------------------------
        */
        $power = $template->fields()->create([
            'type' => 'number',
            'group' => 'Electrical',
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
            'group' => 'Electrical',
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
            'group' => 'Electrical',
            'name' => 'driver_control',
            'position' => 12,
            'options' => [

                [
                    'label' => 'Non-Dimmable (On/Off)',
                    'value' => 'Non-Dimmable (On/Off)',
                ],
                [
                    'label' => 'TRIAC (Phase-Cut - Home dim)',
                    'value' => 'TRIAC',
                ],
                [
                    'label' => '1-10V (Analog - Commercial dim)',
                    'value' => '1-10V',
                ],
                [
                    'label' => '0-10V (Analog - Commercial dim)',
                    'value' => '0-10V',
                ],
                [
                    'label' => 'DALI (Digital - Smart building)',
                    'value' => 'DALI',
                ],
                [
                    'label' => 'DMX (Digital - Color/Stage)',
                    'value' => 'DMX',
                ],
                [
                    'label' => 'Wireless (Casambi, BT - App dim)',
                    'value' => 'Wireless',
                ],
                [
                    'label' => 'Push-button (Switch-Dim)',
                    'value' => 'Push-button',
                ],

            ],
            'en' => ['label' => 'Driver Control'],
            'ar' => ['label' => 'نظام التحكم'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LAMP WATTAGE MAX (Conditional)
        |--------------------------------------------------------------------------
        */
        $lampWattage = $template->fields()->create([
            'type' => 'number',
            'group' => 'Electrical',
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
        | CCT
        |--------------------------------------------------------------------------
        */
        $cct = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'cct',
            'position' => 23,
            'is_required' => false,
            'is_filterable' => true,
            'with_custom' => true,
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
            'en' => [
                'label' => 'Correlated Color Temperature (CCT)',
                'placeholder' => 'Light color warmth (Kelvin)',
            ],
            'ar' => [
                'label' => 'درجة حرارة اللون',
                'placeholder' => 'دفء لون الضوء (كلفن)',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CRI
        |--------------------------------------------------------------------------
        */
        $cri = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'cri',
            'position' => 24,
            'options' => [
                ['label' => 'CRI 70+ (Functional)', 'value' => 'CRI 70+'],
                ['label' => 'CRI 80+ (Standard)', 'value' => 'CRI 80+'],
                ['label' => 'CRI 90+ (High-End)', 'value' => 'CRI 90+'],
                ['label' => 'CRI 95+ (Retail / Art)', 'value' => 'CRI 95+'],
                ['label' => 'CRI 98+ (Museum Grade)', 'value' => 'CRI 98+']
            ],
            'en' => [
                'label' => 'Color Rendering Index (CRI)',
                'placeholder' => 'Color accuracy (0-100)'
            ],

            'ar' => [
                'label' => 'معامل تجسيد اللون',
                'placeholder' => 'دقة تجسيد اللون (0-100)'
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | UGR
        |--------------------------------------------------------------------------
        */
        $ugr = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'ugr',
            'position' => 26,
            'options' => [
                [
                    'label' => 'UGR <16 (High Task - e.g., Drafting)',
                    'UGR <16',
                ],
                [
                    'label' => 'UGR <19 (Standard - Office / Education)',
                    'UGR <19',
                ],
                [
                    'label' => 'UGR <22 (General - Corridors / Industry)',
                    'UGR <22',
                ],
                [
                    'label' => 'UGR <25 (General Industry)',
                    'UGR <25',
                ],
                [
                    'label' => 'UGR >28 (Not specified / High Glare)',
                    'UGR >28',
                ],

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
            'group' => 'Electrical',
            'name' => 'sdcm',
            'position' => 27,
            'options' => [
                [
                    'label' => '1-Step (Imperceptible difference - Museum)',
                    'value' => '1-Step',
                ],
                [
                    'label' => '2-Step (Barely perceptible - High-End Retail)',
                    'value' => '2-Step',
                ],
                [
                    'label' => '3-Step (Noticeable - Professional Standard)',
                    'value' => '3-Step',
                ],
                [
                    'label' => '5-Step (Noticeable - Standard Commercial)',
                    'value' => '5-Step',
                ],
                [
                    'label' => '7-Step (Visible difference - Economy)',
                    'value' => '7-Step',
                ],

            ],
            'en' => [
                'label' => 'MacAdam Ellipse (SDCM)',
                'placeholder' => 'Color consistency between fixtures'
            ],
            'ar' => [
                'label' => 'ثبات اللون (SDCM)',
                'placeholder' => 'Color consistency between fixtures'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | IP RATING
        |--------------------------------------------------------------------------
        */
        $ip = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'ip_rating',
            'position' => 31,
            'is_filterable' => false,
            'options' => [
                [
                    'value' => 'IP20',
                    'label' => 'IP20 (No Protection - Dry indoor)',
                ],
                [
                    'value' => 'IP40',
                    'label' => 'IP40 (Tools/Wires > 1mm)',
                ],
                [
                    'value' => 'IP44',
                    'label' => 'IP44 (Splashes - e.g., Bathroom)',
                ],
                [
                    'value' => 'IP54',
                    'label' => 'IP54 (Dust, Splashes)',
                ],
                [
                    'value' => 'IP65',
                    'label' => 'IP65 (Water Jets - Hosedown)',
                ],
                [
                    'value' => 'IP66',
                    'label' => 'IP66 (Powerful Jets)',
                ],
                [
                    'value' => 'IP67',
                    'label' => 'IP67 (Immersion up to 1m)',
                ],
                [
                    'value' => 'IP68',
                    'label' => 'IP68 (Submersible > 1m)',
                ],
                [
                    'value' => ' IP69K',
                    'label' => 'IP69K (High-Pressure Washdown)',
                ],

            ],
            'en' => [
                'label' => 'Ingress Protection (IP) Rating',
                'placeholder' => 'Dust/Water ingress protection'
            ],
            'ar' => [
                'label' => 'درجة الحماية IP',
                'placeholder' => 'مستوى الحماية من الغبار/الماء'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | IK RATING
        |--------------------------------------------------------------------------
        */
        $ik = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'ik_rating',
            'position' => 32,
            'options' => [

                [
                    'value' => 'IK00',
                    'label' => 'IK00 (No Protection)',
                ],
                [
                    'value' => 'IK02',
                    'label' => 'IK02 (0.2J - Basic Indoor)',
                ],
                [
                    'value' => 'IK05',
                    'label' => 'IK05 (0.7J - Low Traffic)',
                ],
                [
                    'value' => 'IK07',
                    'label' => 'IK07 (2J - High Traffic / Public Access)',
                ],
                [
                    'value' => 'IK08',
                    'label' => 'IK08 (5J - Vandal-Resistant)',
                ],
                [
                    'value' => 'IK10',
                    'label' => 'IK10 (20J - Vandal-Proof / Extreme)',
                ],

            ],
            'en' => [
                'label' => 'Impact Resistance (IK) Rating',
                'placeholder' => 'Impact/Vandal protection'
            ],

            'ar' => [
                'label' => 'مقاومة الصدمات IK',
                'placeholder' => 'مستوى الحماية من الصدمات/التخريب'
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | WARRANTY
        |--------------------------------------------------------------------------
        */
        $warranty = $template->fields()->create([
            'type' => 'select',
            'group' => 'Electrical',
            'name' => 'warranty',
            'position' => 33,
            'options' => [
                '1 Year','2 Years','3 Years',
                '5 Years','7 Years','10 Years',
            ],
            'en' => [
                'label' => 'Warranty',
                'placeholder' => 'Select warranty period'
            ],

            'ar' => [
                'label' => 'الضمان',
                'placeholder' => 'Select warranty period'
            ],

        ]);
    }
}

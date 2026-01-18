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
                [
                    'label' => 'Recessed (Trimmed)',
                    'value' => 'Recessed (Trimmed)',
                ],
                [
                    'label' => 'Recessed (Trimless / Plaster-in)',
                    'value' => 'Recessed (Trimless / Plaster-in)',
                ],
                [
                    'label' => 'Surface Mounted',
                    'value' => 'Surface Mounted',
                ],
                [
                    'label' => 'Suspended (Pendant - from ceiling)',
                    'value' => 'Suspended (Pendant - from ceiling)',
                ],
                [
                    'label' => 'Suspended (Catenary - on horizontal wire)',
                    'value' => 'Suspended (Catenary - on horizontal wire)',
                ],
                [
                    'label' => 'Track (3-Phase / LVM)',
                    'value' => 'Track (3-Phase / LVM)',
                ],
                [
                    'label' => 'Track (Magnetic)',
                    'value' => 'Track (Magnetic)',
                ],
                [
                    'label' => 'Wall Mounted (Surface)',
                    'value' => 'Wall Mounted (Surface)',
                ],
                [
                    'label' => 'Wall Mounted (Recessed)',
                    'value' => 'Wall Mounted (Recessed)',
                ],
                [
                    'label' => 'Floor Mounted',
                    'value' => 'Floor Mounted',
                ],
                [
                    'label' => 'In-Ground',
                    'value' => 'In-Ground',
                ],
                [
                    'label' => 'Pole Mounted',
                    'value' => 'Pole Mounted',
                ],
                [
                    'label' => 'Bollard',
                    'value' => 'Bollard',
                ],
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
                [
                    'label' => 'Aluminum (Die-cast)',
                    'value' => 'Aluminum (Die-cast)',
                ],

                [
                    'label' => 'Aluminum (Extruded)',
                    'value' => 'Aluminum (Extruded)',
                ],

                [
                    'label' => 'Polycarbonate (PC)',
                    'value' => 'Polycarbonate (PC)',
                ],

                [
                    'label' => 'ABS (Acrylonitrile Butadiene Styrene)',
                    'value' => 'ABS (Acrylonitrile Butadiene Styrene)',
                ],

                [
                    'label' => 'PMMA (Acrylic)',
                    'value' => 'PMMA (Acrylic)',
                ],

                [
                    'label' => 'GRP (Glass-Reinforced Plastic)',
                    'value' => 'GRP (Glass-Reinforced Plastic)',
                ],

                [
                    'label' => 'Stainless Steel (304)',
                    'value' => 'Stainless Steel (304)',
                ],

                [
                    'label' => 'Stainless Steel (316 / Marine Grade)',
                    'value' => 'Stainless Steel (316 / Marine Grade)',
                ],

                [
                    'label' => 'Glass',
                    'value' => 'Glass',
                ],

                [
                    'label' => 'Gypsum / Plaster',
                    'value' => 'Gypsum / Plaster',
                ],

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
                [
                    'label' => 'Standard',
                    'options' => [
                        [
                            'label'=>'White (Matte)',
                            'value'=>'White (Matte)',
                        ],
                        [
                            'label'=>'White (Gloss)',
                            'value'=>'White (Gloss)',
                        ],
                        [
                            'label'=>'Black (Matte)',
                            'value'=>'Black (Matte)',
                        ],
                        [
                            'label'=>'Black (Gloss)',
                            'value'=>'Black (Gloss)',
                        ],
                        [
                            'label'=>'Grey / Silver',
                            'value'=>'Grey / Silver',
                        ],
                        [
                            'label'=>'Graphite / Anthracite',
                            'value'=>'Graphite / Anthracite',
                        ],
                    ],
                ],
                [
                    'label' => 'Metallic',
                    'options' => [
                        [
                            'label' => 'Anodized Aluminum',
                            'value' => 'Anodized Aluminum',
                        ],
                        [
                            'label' => 'Brushed Aluminum',
                            'value' => 'Brushed Aluminum',
                        ],
                        [
                            'label' => 'Chrome (Polished)',
                            'value' => 'Chrome (Polished)',
                        ],
                        [
                            'label' => 'Bronze',
                            'value' => 'Bronze',
                        ],
                        [
                            'label' => 'Brass',
                            'value' => 'Brass',
                        ],
                        [
                            'label' => 'Copper',
                            'value' => 'Copper',
                        ],
                    ],
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
                [
                    'label' => 'Opal / Frosted (Uniform light)',
                    'value' => 'Opal / Frosted (Uniform light)',
                ],
                [
                    'label' => 'Microprismatic (Low Glare - UGR<19)',
                    'value' => 'Microprismatic (Low Glare - UGR<19)',
                ],
                [
                    'label' => 'Baffle / Darklight (Deep recessed)',
                    'value' => 'Baffle / Darklight (Deep recessed)',
                ],
                [
                    'label' => 'Louvre (Grid cover)',
                    'value' => 'Louvre (Grid cover)',
                ],
                [
                    'label' => 'Lens (Clear/Spot)',
                    'value' => 'Lens (Clear/Spot)',
                ],
                [
                    'label' => 'Asymmetric Lens (Wall-wash)',
                    'value' => 'Asymmetric Lens (Wall-wash)',
                ],
                [
                    'label' => 'Clear Cover (Protective)',
                    'value' => 'Clear Cover (Protective)',
                ],
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
                [
                    'label' => 'Standard Spots',
                    'options' => [
                        [
                            'label'=>'< 18°',
                            'value'=>'< 18°',
                        ],
                        [
                            'label'=>'18°-29°',
                            'value'=>'18°-29°',
                        ],
                        [
                            'label'=>'29°-46°',
                            'value'=>'29°-46°',
                        ],
                    ],
                ],
                [
                    'label' => 'Standard Floods',
                    'options' => [
                        [
                            'label'=>'46°-70°',
                            'value'=>'46°-70°',
                        ],
                        [
                            'label'=>'70°-100°',
                            'value'=>'70°-100°',
                        ],
                        [
                            'label'=>'> 100°',
                            'value'=>'> 100°',
                        ],
                    ],
                ],
                [
                    'label' => 'Specialist',
                    'options' => [
                        [
                            'label'=>'Asymmetric',
                            'value'=>'Asymmetric',
                        ],
                        [
                            'label'=>'Double Asymmetric',
                            'value'=>'Double Asymmetric',
                        ],
                    ],
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
            'options' => [
                [
                    'label' => 'Linear',
                    'value' => 'Linear',
                ],
                [
                    'label' => 'Rectangular',
                    'value' => 'Rectangular',
                ],
                [
                    'label' => 'Square',
                    'value' => 'Square',
                ],
                [
                    'label' => 'Round',
                    'value' => 'Round'
                ],
            ],
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
                [
                    'label' => 'Integrated LED (Chip is built-in)',
                    'value' => 'Integrated LED',
                ],
                [
                    'label' => 'Replaceable Lamp (Fixture is a holder/socket)',
                    'value' => 'Replaceable Lamp',
                ],
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
                [
                    'label' => 'SMD (Surface Mount Device - e.g., 2835, 5050)',
                    'value' => 'SMD',
                ],
                [
                    'label' => 'COB (Chip on Board - Single large chip)',
                    'value' => 'COB ',
                ],
                [
                    'label' => 'MCOB (Multi-Chip on Board)',
                    'value' => 'MCOB',
                ],
                [
                    'label' => 'Filament (LED)',
                    'value' => 'Filament',
                ],
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
                [
                    'label' => 'E27 (Edison Screw)',
                    'value' => 'E27',
                ],
                [
                    'label' => 'E14 (Small Edison Screw)',
                    'value' => 'E14',
                ],
                [
                    'label' => 'GU10 (Twist-Lock)',
                    'value' => 'GU10',
                ],
                [
                    'label' => 'MR16 / GU5.3 (Low Voltage Pins)',
                    'value' => 'MR16 / GU5.3',
                ],
                [
                    'label' => 'G9 (Bi-pin)',
                    'value' => 'G9',
                ],
                [
                    'label' => 'G4 (Bi-pin)',
                    'value' => 'G4',
                ],
                [
                    'label' => 'T8 / G13 (Linear Tube)',
                    'value' => 'T8 / G13',
                ],
                [
                    'label' => 'T5 / G5 (Linear Tube)',
                    'value' => 'T5 / G5',
                ],
                [
                    'label' => 'AR111 / G53',
                    'value' => 'AR111 / G53',
                ],
                [
                    'label' => 'GX53',
                    'value' => 'GX53',
                ],
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
            'options' => [
                [
                    'label' => 'Yes',
                    'value' => 'Yes',
                ],
                [
                    'label' => 'No',
                    'value' => 'No',
                ],
            ],
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
                [
                    'label' => '15000 hrs',
                    'value' => '15000 hrs',
                ],
                [
                    'label' => '25000 hrs',
                    'value' => '25000 hrs',
                ],
                [
                    'label' => '30000 hrs',
                    'value' => '30000 hrs',
                ],
                [
                    'label' => '35000 hrs',
                    'value' => '35000 hrs',
                ],
                [
                    'label' => '50000 hrs',
                    'value' => '50000 hrs',
                ],
                [
                    'label' => '60000 hrs',
                    'value' => '60000 hrs',
                ],
                [
                    'label' => '75000 hrs',
                    'value' => '75000 hrs',
                ],
                [
                    'label' => '100000 hrs',
                    'value' => '100000 hrs',
                ],
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
                [
                    'label' => '220-240V AC',
                    'value' => '220-240V AC',
                ],
                [
                    'label' => '100-277V AC',
                    'value' => '100-277V AC',
                ],
                [
                    'label' => '12V DC',
                    'value' => '12V DC',
                ],
                [
                    'label' => '24V DC',
                    'value' => '24V DC',
                ],
                [
                    'label' => '48V DC',
                    'value' => '48V DC',
                ],
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
                'placeholder' => 'Select or type Custom kelvin value',
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
                [
                    'label' => 'CRI 70+ (Functional)',
                    'value' => 'CRI 70+'
                ],
                [
                    'label' => 'CRI 80+ (Standard)',
                    'value' => 'CRI 80+'
                ],
                [
                    'label' => 'CRI 90+ (High-End)',
                    'value' => 'CRI 90+'
                ],
                [
                    'label' => 'CRI 95+ (Retail / Art)',
                    'value' => 'CRI 95+'
                ],
                [
                    'label' => 'CRI 98+ (Museum Grade)',
                    'value' => 'CRI 98+'
                ]
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
                    'value' => 'UGR <16',
                ],
                [
                    'label' => 'UGR <19 (Standard - Office / Education)',
                    'value' => 'UGR <19',
                ],
                [
                    'label' => 'UGR <22 (General - Corridors / Industry)',
                    'value' => 'UGR <22',
                ],
                [
                    'label' => 'UGR <25 (General Industry)',
                    'value' => 'UGR <25',
                ],
                [
                    'label' => 'UGR >28 (Not specified / High Glare)',
                    'value' => 'UGR >28',
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
                [
                    'label' => '1 Year',
                    'value' => '1 Year',
                ],
                [
                    'label' => '2 Years',
                    'value' => '2 Years',
                ],
                [
                    'label' => '3 Years',
                    'value' => '3 Years',
                ],
                [
                    'label' => '5 Years',
                    'value' => '5 Years',
                ],
                [
                    'label' => '7 Years',
                    'value' => '7 Years',
                ],
                [
                    'label' => '10 Years',
                    'value' => '10 Years',
                ],
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

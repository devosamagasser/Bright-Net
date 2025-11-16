<?php

namespace Database\Seeders;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $solutions = [
            [
                'translations' => [
                    'en' => ['name' => 'Lighting'],
                    'ar' => ['name' => 'الإضاءة'],
                ],
                'departments' => [
                    [
                        'translations' => [
                            'en' => ['name' => 'Indoor Lighting'],
                            'ar' => ['name' => 'إضاءة داخلية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Downlight'],              'ar' => ['name' => 'داون لايت']]],
                            ['translations' => ['en' => ['name' => 'Spotlight'],              'ar' => ['name' => 'سبوت لايت']]],
                            ['translations' => ['en' => ['name' => 'Track Light'],            'ar' => ['name' => 'إضاءة مسار']]],
                            ['translations' => ['en' => ['name' => 'Linear'],                 'ar' => ['name' => 'إضاءة خطية']]],
                            ['translations' => ['en' => ['name' => 'Pendant'],                'ar' => ['name' => 'إضاءة معلقة']]],
                            ['translations' => ['en' => ['name' => 'Troffer / Panel'],        'ar' => ['name' => 'بانل / تروفر']]],
                            ['translations' => ['en' => ['name' => 'High Bay'],               'ar' => ['name' => 'هاي باي']]],
                            ['translations' => ['en' => ['name' => 'Low Bay'],                'ar' => ['name' => 'لو باي']]],
                            ['translations' => ['en' => ['name' => 'Wall-Washer'],            'ar' => ['name' => 'وول واشر']]],
                            ['translations' => ['en' => ['name' => 'Wall-Grazer'],            'ar' => ['name' => 'وول جريزر']]],
                            ['translations' => ['en' => ['name' => 'Wall Mounted (Indoor)'],  'ar' => ['name' => 'إضاءة جدارية داخلية']]],
                            ['translations' => ['en' => ['name' => 'LED Flex / Strip'],       'ar' => ['name' => 'ليد فليكس / سترب']]],
                            ['translations' => ['en' => ['name' => 'Emergency / Exit'],       'ar' => ['name' => 'طوارئ / مخارج']]],
                            ['translations' => ['en' => ['name' => 'Fittings & Accessories'], 'ar' => ['name' => 'اكسسوارات داخلية']]],
                        ],
                    ],
                    [
                        'translations' => [
                            'en' => ['name' => 'Outdoor Lighting'],
                            'ar' => ['name' => 'إضاءة خارجية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Floodlight'],                  'ar' => ['name' => 'كشاف']]],
                            ['translations' => ['en' => ['name' => 'Wall Pack'],                   'ar' => ['name' => 'وول باك']]],
                            ['translations' => ['en' => ['name' => 'Bollard'],                     'ar' => ['name' => 'بولارد']]],
                            ['translations' => ['en' => ['name' => 'In-Ground'],                   'ar' => ['name' => 'إن جراوند']]],
                            ['translations' => ['en' => ['name' => 'Streetlight'],                 'ar' => ['name' => 'إضاءة شوارع']]],
                            ['translations' => ['en' => ['name' => 'Post Top'],                    'ar' => ['name' => 'بوست توب']]],
                            ['translations' => ['en' => ['name' => 'Downlight (IP Rated)'],        'ar' => ['name' => 'داون لايت خارجي']]],
                            ['translations' => ['en' => ['name' => 'Spotlight (IP Rated)'],        'ar' => ['name' => 'سبوت لايت خارجي']]],
                            ['translations' => ['en' => ['name' => 'Linear (IP Rated)'],           'ar' => ['name' => 'لينيار خارجي']]],
                            ['translations' => ['en' => ['name' => 'Pendant (IP Rated)'],          'ar' => ['name' => 'إضاءة معلقة خارجية']]],
                            ['translations' => ['en' => ['name' => 'Wall-Washer (IP Rated)'],      'ar' => ['name' => 'وول واشر خارجي']]],
                            ['translations' => ['en' => ['name' => 'Wall-Grazer (IP Rated)'],      'ar' => ['name' => 'وول جريزر خارجي']]],
                            ['translations' => ['en' => ['name' => 'Wall Mounted (Outdoor)'],      'ar' => ['name' => 'إضاءة جدارية خارجية']]],
                            ['translations' => ['en' => ['name' => 'LED Flex / Strip (IP Rated)'], 'ar' => ['name' => 'سترب خارجي']]],
                            ['translations' => ['en' => ['name' => 'Emergency / Exit'],            'ar' => ['name' => 'طوارئ / مخارج خارجية']]],
                            ['translations' => ['en' => ['name' => 'Fittings & Accessories'],      'ar' => ['name' => 'اكسسوارات خارجية']]],
                        ],
                    ],
                ],
            ]
        ];

        foreach ($solutions as $solutionData) {
            $solution = Solution::create([
                'translations' => $solutionData['translations'],
            ]);

            foreach ($solutionData['departments'] as $departmentData) {
                $department = $solution->departments()->create([
                    'translations' => $departmentData['translations'],
                ]);

                foreach ($departmentData['subcategories'] as $subcategoryData) {
                    $department->subcategories()->create([
                        'translations' => $subcategoryData['translations'],
                    ]);
                }
            }
        }
    }
}




Recommended Applications: [Multi-Select Checkbox] - Tag all suitable use cases
○ If Environment = Indoor:
■ Office & Corporate
■ Retail & Showroom
■ Hospitality (Hotels, Restaurants)
■ Residential & Living
■ Culture (Museums, Galleries)
■ Education (Schools, Libraries)
■ Healthcare
■ Industrial & Logistics
■ Sports (Indoor)
■ Food & Beverage Processing
■ Pharmaceutical / Cleanroom
○ If Environment = Outdoor:
■ Facade & Structure
■ Landscape & Garden
■ Public Spaces & Plazas
■ Road & Street
■ Tunnel
■ Sports & Area
■ Water Features (Pools, Fountains)
■ Marine (Saltwater / Coastal)


Installation Type: [Dropdown] - Mounting method
○ Recessed (Trimmed)
○ Recessed (Trimless / Plaster-in)
○ Surface Mounted
○ Suspended (Pendant - from ceiling)
○ Suspended (Catenary - on horizontal wire)
○ Track (3-Phase / LVM)
○ Track (Magnetic)
○ Wall Mounted (Surface)
○ Wall Mounted (Recessed)
○ Floor Mounted
○ In-Ground
○ Pole Mounted
○ Bollard

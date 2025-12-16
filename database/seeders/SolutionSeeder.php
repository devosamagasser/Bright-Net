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
                            ['translations' => ['en' => ['name' => 'Downlight / Spotlight'],             'ar' => ['name' => 'داون لايت']]],
                            ['translations' => ['en' => ['name' => 'Track Light'],           'ar' => ['name' => 'إضاءة مسار']]],
                            ['translations' => ['en' => ['name' => 'Linear Profile'],        'ar' => ['name' => 'إضاءة خطية']]],
                            ['translations' => ['en' => ['name' => 'Pendant'],               'ar' => ['name' => 'إضاءة معلقة']]],
                            ['translations' => ['en' => ['name' => 'Troffer / Panel'],       'ar' => ['name' => 'بانل / تروفر']]],
                            ['translations' => ['en' => ['name' => 'High/Low Bay'],          'ar' => ['name' => 'هاي باي']]],
                            ['translations' => ['en' => ['name' => 'Wall-Washer/Grazer'],    'ar' => ['name' => 'وول واشر']]],
                            ['translations' => ['en' => ['name' => 'Wall Mounted (Indoor)'], 'ar' => ['name' => 'إضاءة جدارية داخلية']]],
                            ['translations' => ['en' => ['name' => 'LED Flex / Strip'],      'ar' => ['name' => 'ليد فليكس / سترب']]],
                            ['translations' => ['en' => ['name' => 'Emergency / Exit'],      'ar' => ['name' => 'طوارئ / مخارج']]],
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
                            ['translations' => ['en' => ['name' => 'Streetlight / Post Top'],      'ar' => ['name' => 'إضاءة شوارع']]],
                            ['translations' => ['en' => ['name' => 'Downlight / Spotlight (IP Rated)'],        'ar' => ['name' => 'داون لايت خارجي']]],
                            ['translations' => ['en' => ['name' => 'Linear (IP Rated)'],           'ar' => ['name' => 'لينيار خارجي']]],
                            ['translations' => ['en' => ['name' => 'Pendant (IP Rated)'],          'ar' => ['name' => 'إضاءة معلقة خارجية']]],
                            ['translations' => ['en' => ['name' => 'Wall-Washer / Grazer (IP Rated)'],      'ar' => ['name' => 'وول واشر خارجي']]],
                            ['translations' => ['en' => ['name' => 'Wall Mounted (Outdoor)'],      'ar' => ['name' => 'إضاءة جدارية خارجية']]],
                            ['translations' => ['en' => ['name' => 'LED Flex / Strip (IP Rated)'], 'ar' => ['name' => 'سترب خارجي']]],
                            ['translations' => ['en' => ['name' => 'Emergency / Exit'],            'ar' => ['name' => 'طوارئ / مخارج خارجية']]],
                        ],

                    ],
                    [
                        'translations' => [
                            'en' => ['name' => 'Fittings & Accessories'],
                            'ar' => ['name' => 'إكسسوارات'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Driver (LED Power Supply)'],            'ar' => ['name' => 'مزود طاقة ليد']]],
                            ['translations' => ['en' => ['name' => 'Emergency (Battery / Inverter)'],       'ar' => ['name' => 'بطارية / عاكس']]],
                            ['translations' => ['en' => ['name' => 'Control (Sensor, Dimmer, Module)'],     'ar' => ['name' => 'مستشعر ، مخفت ، وحدة']]],
                            ['translations' => ['en' => ['name' => 'Mounting (Kit, Bracket)'],              'ar' => ['name' => 'عدة تركيب ، حامل']]],
                            ['translations' => ['en' => ['name' => 'Light Control (Snoot, Filter, Louvre)'],'ar' => ['name' => 'سنوت ، فلتر ، لوفر']]],
                            ['translations' => ['en' => ['name' => 'Connector / Cable'],                    'ar' => ['name' => 'موصل / كابل']]],
                            ['translations' => ['en' => ['name' => 'Replacement Part (Lens, Cover)'],       'ar' => ['name' => 'قطعة غيار (عدسة ، غطاء)']]],
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

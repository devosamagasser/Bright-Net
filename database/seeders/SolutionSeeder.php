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
                    'en' => ['name' => 'Network Infrastructure'],
                    'ar' => ['name' => 'البنية التحتية للشبكات'],
                ],
                'departments' => [
                    [
                        'translations' => [
                            'en' => ['name' => 'Switching'],
                            'ar' => ['name' => 'المبدلات'],
                        ],
                        'subcategories' => [
                            [
                                'translations' => [
                                    'en' => ['name' => 'Core Switching'],
                                    'ar' => ['name' => 'المبدلات المركزية'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Edge Switching'],
                                    'ar' => ['name' => 'مبدلات الأطراف'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'translations' => [
                            'en' => ['name' => 'Wireless'],
                            'ar' => ['name' => 'الشبكات اللاسلكية'],
                        ],
                        'subcategories' => [
                            [
                                'translations' => [
                                    'en' => ['name' => 'Indoor Wi-Fi'],
                                    'ar' => ['name' => 'واي فاي داخلي'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Outdoor Wi-Fi'],
                                    'ar' => ['name' => 'واي فاي خارجي'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'translations' => [
                    'en' => ['name' => 'Security & Monitoring'],
                    'ar' => ['name' => 'الأمن والمراقبة'],
                ],
                'departments' => [
                    [
                        'translations' => [
                            'en' => ['name' => 'Surveillance'],
                            'ar' => ['name' => 'المراقبة'],
                        ],
                        'subcategories' => [
                            [
                                'translations' => [
                                    'en' => ['name' => 'IP Cameras'],
                                    'ar' => ['name' => 'كاميرات الشبكة'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Video Analytics'],
                                    'ar' => ['name' => 'تحليلات الفيديو'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'translations' => [
                            'en' => ['name' => 'Access Control'],
                            'ar' => ['name' => 'التحكم في الدخول'],
                        ],
                        'subcategories' => [
                            [
                                'translations' => [
                                    'en' => ['name' => 'Door Controllers'],
                                    'ar' => ['name' => 'وحدات تحكم الأبواب'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Biometric Readers'],
                                    'ar' => ['name' => 'قارئات بيومترية'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 🔹 إنشاء الحلول مع الترجمات والعلاقات
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

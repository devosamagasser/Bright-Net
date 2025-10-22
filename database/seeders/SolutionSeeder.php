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
                            [
                                'translations' => [
                                    'en' => ['name' => 'Downlights'],
                                    'ar' => ['name' => 'الإضاءة السقفية'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Spotlights'],
                                    'ar' => ['name' => 'الإضاءة الموجهة'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Panel Lights'],
                                    'ar' => ['name' => 'إضاءة الألواح'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Track Lights'],
                                    'ar' => ['name' => 'إضاءة المسار'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Recessed Lighting'],
                                    'ar' => ['name' => 'إضاءة داخل الحوائط أو الأسقف'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Ceiling Lights'],
                                    'ar' => ['name' => 'إضاءة السقف'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'translations' => [
                            'en' => ['name' => 'Outdoor Lighting'],
                            'ar' => ['name' => 'إضاءة خارجية'],
                        ],
                        'subcategories' => [
                            [
                                'translations' => [
                                    'en' => ['name' => 'Street Lights'],
                                    'ar' => ['name' => 'إضاءة الشوارع'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Flood Lights'],
                                    'ar' => ['name' => 'كشافات'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Bollards'],
                                    'ar' => ['name' => 'إضاءة الأعمدة القصيرة'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Wall Mounted'],
                                    'ar' => ['name' => 'إضاءة الجدران'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Landscape Lighting'],
                                    'ar' => ['name' => 'إضاءة الحدائق والمناظر'],
                                ],
                            ],
                            [
                                'translations' => [
                                    'en' => ['name' => 'Step Lights'],
                                    'ar' => ['name' => 'إضاءة السلالم'],
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

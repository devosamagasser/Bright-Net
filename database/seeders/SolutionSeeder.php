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

                    // 1. Indoor Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Indoor Lighting'],
                            'ar' => ['name' => 'إضاءة داخلية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Downlights'], 'ar' => ['name' => 'الإضاءة السقفية']]],
                            ['translations' => ['en' => ['name' => 'Spotlights'], 'ar' => ['name' => 'الإضاءة الموجهة']]],
                            ['translations' => ['en' => ['name' => 'Panel Lights'], 'ar' => ['name' => 'إضاءة الألواح']]],
                            ['translations' => ['en' => ['name' => 'Linear Lights'], 'ar' => ['name' => 'الإضاءة الخطية']]],
                            ['translations' => ['en' => ['name' => 'Track Lights'], 'ar' => ['name' => 'إضاءة المسار']]],
                            ['translations' => ['en' => ['name' => 'Ceiling Lights'], 'ar' => ['name' => 'إضاءة السقف']]],
                            ['translations' => ['en' => ['name' => 'Recessed Lighting'], 'ar' => ['name' => 'إضاءة داخل الأسقف أو الجدران']]],
                            ['translations' => ['en' => ['name' => 'Surface Mounted'], 'ar' => ['name' => 'إضاءة مثبتة على السطح']]],
                        ],
                    ],

                    // 2. Outdoor Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Outdoor Lighting'],
                            'ar' => ['name' => 'إضاءة خارجية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Street Lights'], 'ar' => ['name' => 'إضاءة الشوارع']]],
                            ['translations' => ['en' => ['name' => 'Flood Lights'], 'ar' => ['name' => 'كشافات']]],
                            ['translations' => ['en' => ['name' => 'Landscape Lighting'], 'ar' => ['name' => 'إضاءة المناظر الطبيعية']]],
                            ['translations' => ['en' => ['name' => 'Wall Mounted'], 'ar' => ['name' => 'إضاءة الجدران']]],
                            ['translations' => ['en' => ['name' => 'Bollards'], 'ar' => ['name' => 'إضاءة الأعمدة القصيرة']]],
                            ['translations' => ['en' => ['name' => 'Step Lights'], 'ar' => ['name' => 'إضاءة السلالم']]],
                            ['translations' => ['en' => ['name' => 'Tunnel Lights'], 'ar' => ['name' => 'إضاءة الأنفاق']]],
                            ['translations' => ['en' => ['name' => 'Facade Lighting'], 'ar' => ['name' => 'إضاءة الواجهات']]],
                        ],
                    ],

                    // 3. Industrial Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Industrial Lighting'],
                            'ar' => ['name' => 'إضاءة صناعية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'High Bay Lights'], 'ar' => ['name' => 'إضاءة مرتفعة (High Bay)']]],
                            ['translations' => ['en' => ['name' => 'Low Bay Lights'], 'ar' => ['name' => 'إضاءة منخفضة (Low Bay)']]],
                            ['translations' => ['en' => ['name' => 'Explosion Proof'], 'ar' => ['name' => 'مضادة للانفجار']]],
                            ['translations' => ['en' => ['name' => 'Warehouse Lights'], 'ar' => ['name' => 'إضاءة المخازن']]],
                            ['translations' => ['en' => ['name' => 'Factory Lights'], 'ar' => ['name' => 'إضاءة المصانع']]],
                        ],
                    ],

                    // 4. Architectural Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Architectural Lighting'],
                            'ar' => ['name' => 'الإضاءة المعمارية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Cove Lighting'], 'ar' => ['name' => 'إضاءة الحواف']]],
                            ['translations' => ['en' => ['name' => 'Wall Grazers'], 'ar' => ['name' => 'إضاءة تبرز تفاصيل الجدران']]],
                            ['translations' => ['en' => ['name' => 'Facade Lighting'], 'ar' => ['name' => 'إضاءة الواجهات']]],
                            ['translations' => ['en' => ['name' => 'Accent Lighting'], 'ar' => ['name' => 'إضاءة التأكيد']]],
                            ['translations' => ['en' => ['name' => 'Landscape Architectural'], 'ar' => ['name' => 'إضاءة المناظر المعمارية']]],
                        ],
                    ],

                    // 5. Emergency & Safety Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Emergency & Safety Lighting'],
                            'ar' => ['name' => 'إضاءة الطوارئ والسلامة'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Exit Signs'], 'ar' => ['name' => 'علامات الخروج']]],
                            ['translations' => ['en' => ['name' => 'Emergency Bulkheads'], 'ar' => ['name' => 'إضاءة الطوارئ الجدارية']]],
                            ['translations' => ['en' => ['name' => 'Battery Backup Lights'], 'ar' => ['name' => 'إضاءة ببطارية احتياطية']]],
                            ['translations' => ['en' => ['name' => 'Fire Exit Lighting'], 'ar' => ['name' => 'إضاءة مخارج الحريق']]],
                        ],
                    ],

                    // 6. Decorative Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Decorative Lighting'],
                            'ar' => ['name' => 'الإضاءة الديكورية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Chandeliers'], 'ar' => ['name' => 'الثريات']]],
                            ['translations' => ['en' => ['name' => 'Pendant Lights'], 'ar' => ['name' => 'إضاءة معلقة']]],
                            ['translations' => ['en' => ['name' => 'Wall Sconces'], 'ar' => ['name' => 'إضاءة الحوائط الزخرفية']]],
                            ['translations' => ['en' => ['name' => 'Table Lamps'], 'ar' => ['name' => 'مصابيح الطاولات']]],
                            ['translations' => ['en' => ['name' => 'Floor Lamps'], 'ar' => ['name' => 'مصابيح أرضية']]],
                            ['translations' => ['en' => ['name' => 'String Lights'], 'ar' => ['name' => 'سلاسل الإضاءة']]],
                        ],
                    ],

                    // 7. Smart Lighting
                    [
                        'translations' => [
                            'en' => ['name' => 'Smart Lighting'],
                            'ar' => ['name' => 'الإضاءة الذكية'],
                        ],
                        'subcategories' => [
                            ['translations' => ['en' => ['name' => 'Smart Bulbs'], 'ar' => ['name' => 'المصابيح الذكية']]],
                            ['translations' => ['en' => ['name' => 'Smart Switches'], 'ar' => ['name' => 'المفاتيح الذكية']]],
                            ['translations' => ['en' => ['name' => 'Smart Panels'], 'ar' => ['name' => 'ألواح الإضاءة الذكية']]],
                            ['translations' => ['en' => ['name' => 'Wireless Dimmers'], 'ar' => ['name' => 'مخفتات الإضاءة اللاسلكية']]],
                            ['translations' => ['en' => ['name' => 'Smart Outdoor Lights'], 'ar' => ['name' => 'إضاءة خارجية ذكية']]],
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

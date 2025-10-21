<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Geography\Domain\Models\Region;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Faker\Factory as Faker;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $brandNames = [
            'Cisco', 'Hikvision', 'Aruba Networks', 'TP-Link', 'Dahua', 'Juniper', 'Fortinet',
        ];

        foreach ($brandNames as $name) {
            // 🗺️ اختار منطقة عشوائية
            $region = Region::inRandomOrder()->first();

            // 🧩 أنشئ البراند
            $brand = Brand::firstOrCreate([
                'name' => $name,
            ], [
                'region_id' => $region?->id,
            ]);

            // ⚙️ اختار حلول عشوائية
            $solutions = Solution::inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray();

            // 🔗 اربط الحلول
            $brand->solutions()->syncWithoutDetaching($solutions);

            // 🏗️ بناء الأقسام الخاصة بالحلول اللي اختارها
            $departments = Department::whereIn('solution_id', $solutions)
                ->inRandomOrder()
                ->take(rand(1, 3))
                ->get();

            // 🔗 اربط الأقسام
            $brand->departments()->syncWithoutDetaching($departments->pluck('id')->toArray());
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Brand, Company, Solution, Supplier, SupplierSolution};

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // 🏢 هجيب كل الشركات اللي نوعها supplier
        $companies = Company::where('type', 'supplier')->get();

        foreach ($companies as $company) {
            // 🔹 أنشئ الـ Supplier لو مش موجود
            $supplier = Supplier::firstOrCreate(['id' => $company->id]);

            // ⚙️ اختار حلول عشوائية
            $solutions = Solution::inRandomOrder()->take(rand(1, 2))->get();

            foreach ($solutions as $solution) {
                // 🔗 أنشئ العلاقة بين المورد والسوليوشن
                $supplierSolution = SupplierSolution::firstOrCreate([
                    'supplier_id' => $supplier->id,
                    'solution_id' => $solution->id,
                ]);

                // 🎯 هات البراندات المرتبطة بالسوليوشن ده فقط
                $brandIds = $solution->brands()
                    ->inRandomOrder()
                    ->take(rand(1, 3))
                    ->pluck('brands.id') // نستخدم اسم الجدول لتفادي الالتباس
                    ->toArray();

                // لو مفيش براندات مرتبطة بالسوليوشن ده، تجاهل
                if (empty($brandIds)) {
                    continue;
                }

                // 🔗 اربط البراندات المختارة بالسوليوشن المورد
                $supplierSolution->brands()->syncWithoutDetaching($brandIds);
            }
        }

        $this->command->info('✅ Seeded suppliers with solutions and related brands successfully.');
    }
}

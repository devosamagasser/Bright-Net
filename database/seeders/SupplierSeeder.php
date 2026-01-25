<?php

namespace Database\Seeders;

use App\Models\{Supplier, SupplierBrand, SupplierDepartment, SupplierSolution};
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 🏢 هجيب كل الشركات اللي نوعها supplier
            $company = Company::firstOrCreate([
                'contact_email' =>  "info@sirajlighting.com",
                'type' => CompanyType::SUPPLIER->value,
            ],[
                'name' => " Siraj Lighting",
                'contact_phone' =>  "+20 2 2526 0015",
                'website' =>  "https://sirajlighting.com",
                'description' => " Egyptian supplier specialized in architectural and outdoor lighting solutions.",
            ]);

            $supplier = Supplier::firstOrCreate([
                'company_id' => $company->id,
            ]);
            // ⚙️ اختار حلول عشوائية
            $solution = Solution::first();

            // 🔗 أنشئ العلاقة بين المورد والسوليوشن
            $supplierSolution = SupplierSolution::firstOrCreate([
                'supplier_id' => $supplier->id,
                'solution_id' => $solution->id,
            ]);

            // 🧭 الأقسام المرتبطة بالسوليوشن ده
            $departmentIds = $solution->departments()
                ->inRandomOrder()
                ->take(rand(1, 3))
                ->pluck('departments.id')
                ->toArray();

            $brandIds = Brand::inRandomOrder()
                ->take(rand(1, 4))
                ->pluck('id')
                ->toArray();


            foreach ($brandIds as $brandId){
                $supplierBrand = SupplierBrand::create([
                    'brand_id' => $brandId,
                    'supplier_solution_id' => $supplierSolution->id
                ]);
                $supplierDepartments = collect($departmentIds)->map(function ($departmentId) use ($supplierBrand) {
                    return [
                        'supplier_brand_id' => $supplierBrand->id,
                        'department_id' => $departmentId
                    ];
                })->toArray();
                SupplierDepartment::insert($supplierDepartments);
            }

        });

        $this->command->info('✅ Seeded suppliers with solutions and related brands successfully.');
    }
}

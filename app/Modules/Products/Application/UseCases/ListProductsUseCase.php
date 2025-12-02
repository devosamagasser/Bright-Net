<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    /**
     * @return Collection<int, ProductData>
     */
    public function handle(int $familyId, ?int $supplierId = null): Collection
    {
        $products = $this->products->getByFamily($familyId, $supplierId);
        $roots = $this->serializeRoots(Family::find($familyId));
        return ProductData::collection($products, $roots);
    }


    public static function serializeRoots($family): array
    {
        if(! $family) {
            return [];
        }
        $supplierId = $family->supplier_id;
        $departmentId = $family->subcategory->department_id;
        $locale = app()->getLocale();

        $data = DB::table('brands')
            ->join('supplier_brands', 'brands.id', '=', 'supplier_brands.brand_id')
            ->join('supplier_solutions', 'supplier_brands.supplier_solution_id', '=', 'supplier_solutions.id')
            ->join('supplier_departments', 'supplier_brands.id', '=', 'supplier_departments.supplier_brand_id')
            ->join('solutions', 'solutions.id', '=', 'supplier_solutions.solution_id')
            ->join('departments', 'departments.id', '=', 'supplier_departments.department_id')
            ->leftJoin('solution_translations', function ($join) use ($locale) {
                $join->on('solutions.id', '=', 'solution_translations.solution_id')
                    ->where('solution_translations.locale', '=', $locale);
            })
            ->leftJoin('department_translations', function ($join) use ($locale) {
                $join->on('departments.id', '=', 'department_translations.department_id')
                    ->where('department_translations.locale', '=', $locale);
            })
            ->where('supplier_solutions.supplier_id', $supplierId)
            ->where('supplier_departments.department_id', $departmentId)
            ->select([
                'brands.id as brand_id',
                'brands.name as brand_name',
                'departments.id as department_id',
                'solutions.id as solution_id',
                // ✅ استخدمنا فقط الترجمة
                'department_translations.name as department_name',
                'solution_translations.name as solution_name',
            ])
            ->first();

        return [
            'brand' => [
                'name' => $data->brand_name ?? null,
            ],
            'department' => [
                'name' => $data->department_name ?? null,
            ],
            'solution' => [
                'name' => $data->solution_name ?? null,
            ],
            'subcategory' => [
                'name' => $family->subcategory->name,
                'id' => $family->subcategory->id,
            ],
            'family' => [
                'name' => $family->name,
                'id' => $family->id,
            ],
        ];
    }
}

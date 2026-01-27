<?php

namespace App\Modules\Shared\Domain\Services;

use App\Models\Supplier;
use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class EasyAccessService
{
    /**
     * Get list of solutions for a supplier (id, name only).
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    public function listSolutions(Supplier $supplier): Collection
    {
        $locale = App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        return DB::table('supplier_solutions as ss')
            ->join('solutions as s', 's.id', '=', 'ss.solution_id')
            ->leftJoin('solution_translations as st', function ($join) use ($locale) {
                $join->on('st.solution_id', '=', 's.id')
                    ->where('st.locale', '=', $locale);
            })
            ->leftJoin('solution_translations as fst', function ($join) use ($fallbackLocale) {
                $join->on('fst.solution_id', '=', 's.id')
                    ->where('fst.locale', '=', $fallbackLocale);
            })
            ->where('ss.supplier_id', $supplier->id)
            ->select([
                'ss.id',
                DB::raw('COALESCE(st.name, fst.name) as name'),
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get list of brands for a supplier solution (id, name only).
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    public function listBrands(Supplier $supplier, SupplierSolution $supplierSolution): Collection
    {
        $this->ensureSupplierSolutionBelongsToSupplier($supplierSolution, $supplier->id);

        return DB::table('supplier_brands as sb')
            ->join('brands as b', 'b.id', '=', 'sb.brand_id')
            ->where('sb.supplier_solution_id', $supplierSolution->id)
            ->select([
                'sb.id',
                'b.name',
            ])
            ->orderBy('b.name')
            ->get();
    }

    /**
     * Get list of departments (categories) for a supplier brand (id, name only).
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    public function listDepartments(Supplier $supplier, SupplierBrand $supplierBrand): Collection
    {
        $this->ensureSupplierBrandBelongsToSupplier($supplierBrand, $supplier->id);

        $locale = App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        return DB::table('supplier_departments as sd')
            ->join('departments as d', 'd.id', '=', 'sd.department_id')
            ->leftJoin('department_translations as dt', function ($join) use ($locale) {
                $join->on('dt.department_id', '=', 'd.id')
                    ->where('dt.locale', '=', $locale);
            })
            ->leftJoin('department_translations as fdt', function ($join) use ($fallbackLocale) {
                $join->on('fdt.department_id', '=', 'd.id')
                    ->where('fdt.locale', '=', $fallbackLocale);
            })
            ->where('sd.supplier_brand_id', $supplierBrand->id)
            ->select([
                'sd.id',
                DB::raw('COALESCE(dt.name, fdt.name) as name'),
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get list of subcategories for a supplier department (id, name only).
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    public function listSubcategories(Supplier $supplier, SupplierDepartment $supplierDepartment): Collection
    {
        $departmentId = $this->ensureSupplierDepartmentBelongsToSupplier($supplierDepartment, $supplier->id);

        $locale = App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        return DB::table('subcategories as sc')
            ->leftJoin('subcategory_translations as sct', function ($join) use ($locale) {
                $join->on('sct.subcategory_id', '=', 'sc.id')
                    ->where('sct.locale', '=', $locale);
            })
            ->leftJoin('subcategory_translations as fsct', function ($join) use ($fallbackLocale) {
                $join->on('fsct.subcategory_id', '=', 'sc.id')
                    ->where('fsct.locale', '=', $fallbackLocale);
            })
            ->where('sc.department_id', $departmentId)
            ->select([
                'sc.id',
                DB::raw('COALESCE(sct.name, fsct.name) as name'),
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get list of families for a subcategory and supplier department (id, name only).
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    public function listFamilies(int $subcategoryId, int $supplierDepartmentId, ?int $supplierId = null): Collection
    {
        $query = Family::query()
            ->where('subcategory_id', $subcategoryId)
            ->where('supplier_department_id', $supplierDepartmentId);

        if ($supplierId !== null) {
            $query->where('supplier_id', $supplierId);
        }

        return $query
            ->select(['id', 'name'])
            ->orderBy('order', 'desc')
            ->get()
            ->map(fn (Family $family) => [
                'id' => $family->id,
                'name' => $family->name,
            ]);
    }

    /**
     * Get list of currencies from enum.
     *
     * @return Collection<int, array{id: string, name: string}>
     */
    public function listCurrencies(): Collection
    {
        return collect(PriceCurrency::cases())
            ->map(fn (PriceCurrency $currency) => [
                'id' => $currency->value,
                'name' => $currency->value,
            ]);
    }

    /**
     * Get list of unique origins from products.
     *
     * @return Collection<int, array{id: string, name: string}>
     */
    public function listOrigins(?int $supplierId = null): Collection
    {
        return Product::query()
            ->select('origin')
            ->distinct()
            ->whereNotNull('origin')
            ->where('origin', '!=', '')
            ->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId))
            ->orderBy('origin')
            ->pluck('origin')
            ->map(fn (string $origin) => [
                'id' => $origin,
                'name' => $origin,
            ]);
    }

    /**
     * Ensure the supplier solution belongs to the supplier.
     */
    private function ensureSupplierSolutionBelongsToSupplier(SupplierSolution $supplierSolution, int $supplierId): void
    {
        if ((int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }
    }

    /**
     * Ensure the supplier brand belongs to the supplier.
     */
    private function ensureSupplierBrandBelongsToSupplier(SupplierBrand $supplierBrand, int $supplierId): void
    {
        $supplierBrand->loadMissing('supplierSolution');
        $supplierSolution = $supplierBrand->supplierSolution;

        if ($supplierSolution === null || (int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }
    }

    /**
     * Ensure the supplier department belongs to the supplier and return department_id.
     */
    private function ensureSupplierDepartmentBelongsToSupplier(SupplierDepartment $supplierDepartment, int $supplierId): int
    {
        $supplierDepartment->loadMissing('supplierBrand.supplierSolution');
        $supplierSolution = optional($supplierDepartment->supplierBrand)->supplierSolution;

        if ($supplierSolution === null || (int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }

        return (int) $supplierDepartment->department_id;
    }
}


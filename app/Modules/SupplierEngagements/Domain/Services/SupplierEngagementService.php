<?php

namespace App\Modules\SupplierEngagements\Domain\Services;

use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Companies\Domain\Models\Company;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierEngagementService
{
    /**
     * Retrieve the solutions associated with the given supplier company.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSolutions(Company $company): array
    {
        $supplierId = $this->supplierIdForCompany($company);

        if ($supplierId === null) {
            return [];
        }

        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', $locale);

        $solutions = DB::table('supplier_solutions as ss')
            ->join('solutions as s', 's.id', '=', 'ss.solution_id')
            ->leftJoin('solution_translations as st', function ($join) use ($locale): void {
                $join->on('st.solution_id', '=', 's.id')
                    ->where('st.locale', '=', $locale);
            })
            ->leftJoin('solution_translations as fst', function ($join) use ($fallbackLocale): void {
                $join->on('fst.solution_id', '=', 's.id')
                    ->where('fst.locale', '=', $fallbackLocale);
            })
            ->where('ss.supplier_id', $supplierId)
            ->select([
                'ss.id as supplier_solution_id',
                'ss.solution_id',
                DB::raw('COALESCE(st.name, fst.name) as solution_name'),
            ])
            ->orderBy('ss.id')
            ->get();

        return $solutions->map(static function (object $solution): array {
            $solutionId = (int) $solution->solution_id;

            return [
                'supplier_solution_id' => (int) $solution->supplier_solution_id,
                'solution_id' => $solutionId,
                'solution' => [
                    'id' => $solutionId,
                    'name' => $solution->solution_name,
                ],
            ];
        })->all();
    }

    /**
     * Retrieve the brands for a supplier-solution assignment.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listBrands(Company $company, SupplierSolution $supplierSolution): array
    {
        $supplierId = $this->requireSupplierId($company);
        $this->ensureSupplierSolutionBelongsToSupplier($supplierSolution, $supplierId);

        $brands = DB::table('supplier_brands as sb')
            ->join('brands as b', 'b.id', '=', 'sb.brand_id')
            ->leftJoinSub($this->brandLogoSubquery(), 'brand_logos', function ($join): void {
                $join->on('brand_logos.brand_id', '=', 'b.id');
            })
            ->leftJoin('media as m', 'm.id', '=', 'brand_logos.media_id')
            ->where('sb.supplier_solution_id', $supplierSolution->getKey())
            ->select([
                'sb.id as supplier_brand_id',
                'b.id as brand_id',
                'b.name as brand_name',
                'm.id as media_id',
                'm.disk as media_disk',
                'm.file_name as media_file_name',
            ])
            ->orderBy('b.name')
            ->get();

        return $brands->map(function (object $brand): array {
            return [
                'supplier_brand_id' => (int) $brand->supplier_brand_id,
                'id' => (int) $brand->brand_id,
                'name' => $brand->brand_name,
                'logo' => $this->mediaUrl($brand->media_disk, $brand->media_id, $brand->media_file_name),
            ];
        })->all();
    }

    /**
     * Retrieve the departments shared between the supplier and brand.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listDepartments(Company $company, SupplierBrand $supplierBrand): array
    {
        $supplierId = $this->requireSupplierId($company);
        $this->ensureSupplierBrandBelongsToSupplier($supplierBrand, $supplierId);

        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', $locale);

        $departments = DB::table('supplier_departments as sd')
            ->join('supplier_brands as sb', 'sb.id', '=', 'sd.supplier_brand_id')
            ->join('supplier_solutions as ss', 'ss.id', '=', 'sb.supplier_solution_id')
            ->join('departments as d', 'd.id', '=', 'sd.department_id')
            ->leftJoin('department_translations as dt', function ($join) use ($locale): void {
                $join->on('dt.department_id', '=', 'd.id')
                    ->where('dt.locale', '=', $locale);
            })
            ->leftJoin('department_translations as fdt', function ($join) use ($fallbackLocale): void {
                $join->on('fdt.department_id', '=', 'd.id')
                    ->where('fdt.locale', '=', $fallbackLocale);
            })
            ->where('sd.supplier_brand_id', $supplierBrand->getKey())
            ->where('ss.supplier_id', $supplierId)
            ->select([
                'sd.id as supplier_department_id',
                'd.id as department_id',
                DB::raw('COALESCE(dt.name, fdt.name) as department_name'),
            ])
            ->orderBy('sd.id')
            ->get();

        return $departments->map(static function (object $department): array {
            return [
                'supplier_department_id' => (int) $department->supplier_department_id,
                'id' => (int) $department->department_id,
                'name' => $department->department_name,
            ];
        })->all();
    }

    /**
     * Retrieve the subcategories linked to the given supplier department.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSubcategories(Company $company, SupplierDepartment $supplierDepartment): array
    {
        $supplierId = $this->requireSupplierId($company);
        $departmentId = $this->ensureSupplierDepartmentBelongsToSupplier($supplierDepartment, $supplierId);

        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', $locale);

        $subcategories = DB::table('subcategories as sc')
            ->leftJoin('subcategory_translations as sct', function ($join) use ($locale): void {
                $join->on('sct.subcategory_id', '=', 'sc.id')
                    ->where('sct.locale', '=', $locale);
            })
            ->leftJoin('subcategory_translations as fsct', function ($join) use ($fallbackLocale): void {
                $join->on('fsct.subcategory_id', '=', 'sc.id')
                    ->where('fsct.locale', '=', $fallbackLocale);
            })
            ->where('sc.department_id', $departmentId)
            ->select([
                'sc.id as subcategory_id',
                DB::raw('COALESCE(sct.name, fsct.name) as subcategory_name'),
            ])
            ->orderBy('sc.id')
            ->get();

        return $subcategories->map(static function (object $subcategory): array {
            return [
                'id' => (int) $subcategory->subcategory_id,
                'name' => $subcategory->subcategory_name,
            ];
        })->all();
    }

    private function supplierIdForCompany(Company $company): ?int
    {
        $supplier = DB::table('suppliers')
            ->select('id')
            ->where('company_id', $company->getKey())
            ->first();

        return $supplier?->id !== null ? (int) $supplier->id : null;
    }

    private function requireSupplierId(Company $company): int
    {
        $supplierId = $this->supplierIdForCompany($company);

        if ($supplierId === null) {
            abort(404);
        }

        return $supplierId;
    }

    private function ensureSupplierSolutionBelongsToSupplier(SupplierSolution $supplierSolution, int $supplierId): void
    {
        if ((int) $supplierSolution->supplier_id === $supplierId) {
            return;
        }

        $exists = DB::table('supplier_solutions')
            ->where('id', $supplierSolution->getKey())
            ->where('supplier_id', $supplierId)
            ->exists();

        if (! $exists) {
            abort(404);
        }
    }

    private function ensureSupplierBrandBelongsToSupplier(SupplierBrand $supplierBrand, int $supplierId): void
    {
        $exists = DB::table('supplier_brands as sb')
            ->join('supplier_solutions as ss', 'ss.id', '=', 'sb.supplier_solution_id')
            ->where('sb.id', $supplierBrand->getKey())
            ->where('ss.supplier_id', $supplierId)
            ->exists();

        if (! $exists) {
            abort(404);
        }
    }

    private function ensureSupplierDepartmentBelongsToSupplier(SupplierDepartment $supplierDepartment, int $supplierId): int
    {
        $record = DB::table('supplier_departments as sd')
            ->join('supplier_brands as sb', 'sb.id', '=', 'sd.supplier_brand_id')
            ->join('supplier_solutions as ss', 'ss.id', '=', 'sb.supplier_solution_id')
            ->where('sd.id', $supplierDepartment->getKey())
            ->where('ss.supplier_id', $supplierId)
            ->select('sd.department_id')
            ->first();

        if ($record === null) {
            abort(404);
        }

        return (int) $record->department_id;
    }

    private function brandLogoSubquery(): Builder
    {
        return DB::table('media')
            ->selectRaw('MAX(id) as media_id, model_id as brand_id')
            ->where('model_type', '=', \App\Modules\Brands\Domain\Models\Brand::class)
            ->where('collection_name', '=', 'logo')
            ->groupBy('model_id');
    }

    private function mediaUrl(?string $disk, ?int $mediaId, ?string $fileName): ?string
    {
        if ($disk === null || $mediaId === null || $fileName === null) {
            return null;
        }

        return Storage::disk($disk)->url(sprintf('%d/%s', $mediaId, $fileName));
    }
}

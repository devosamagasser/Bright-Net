<?php

namespace App\Modules\SupplierEngagements\Domain\Services;

use App\Models\Supplier;
use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SupplierEngagementService
{
    /**
     * Retrieve the solutions associated with the given supplier company.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSolutions(Company $company): array
    {
        $supplier = $this->supplierForCompany($company);

        if ($supplier === null) {
            return [];
        }

        $locale = app()->getLocale();
        $fallbackLocale = $this->fallbackLocale($locale);

        return $supplier->solutions()
            ->with('solution.translations')
            ->orderBy('id')
            ->get()
            ->map(function (SupplierSolution $supplierSolution) use ($locale, $fallbackLocale): array {
                $solution = $supplierSolution->solution;
                $solutionId = (int) $supplierSolution->solution_id;

                return [
                    'supplier_solution_id' => (int) $supplierSolution->getKey(),
                    'solution_id' => $solutionId,
                    'solution' => [
                        'id' => $solutionId,
                        'name' => $this->translatedName($solution, $locale, $fallbackLocale),
                    ],
                ];
            })
            ->all();
    }

    /**
     * Retrieve the brands for a supplier-solution assignment.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listBrands(Company $company, SupplierSolution $supplierSolution): array
    {
        $supplier = $this->requireSupplier($company);
        $this->ensureSupplierSolutionBelongsToSupplier($supplierSolution, (int) $supplier->getKey());

        return $supplierSolution->brands()
            ->with('brand.media')
            ->select('supplier_brands.*')
            ->join('brands', 'brands.id', '=', 'supplier_brands.brand_id')
            ->orderBy('brands.name')
            ->get()
            ->map(function (SupplierBrand $supplierBrand): array {
                $brand = $supplierBrand->brand;

                return [
                    'supplier_brand_id' => (int) $supplierBrand->getKey(),
                    'id' => $brand?->getKey() !== null ? (int) $brand->getKey() : null,
                    'name' => $brand?->name,
                    'logo' => $brand?->getFirstMediaUrl('logo') ?: null,
                ];
            })
            ->all();
    }

    /**
     * Retrieve the departments shared between the supplier and brand.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listDepartments(Company $company, SupplierBrand $supplierBrand): array
    {
        $supplier = $this->requireSupplier($company);
        $this->ensureSupplierBrandBelongsToSupplier($supplierBrand, (int) $supplier->getKey());

        $locale = app()->getLocale();
        $fallbackLocale = $this->fallbackLocale($locale);

        return $supplierBrand->departments()
            ->with('department.media', 'department.translations')
            ->orderBy('supplier_departments.id')
            ->get()
            ->map(function (SupplierDepartment $supplierDepartment) use ($locale, $fallbackLocale): array {
                $department = $supplierDepartment->department;

                return [
                    'supplier_department_id' => (int) $supplierDepartment->getKey(),
                    'id' => (int) $supplierDepartment->department_id,
                    'name' => $this->translatedName($department, $locale, $fallbackLocale),
                    'cover' => $department?->getFirstMediaUrl('cover') ?: null,
                ];
            })
            ->all();
    }

    /**
     * Retrieve the subcategories linked to the given supplier department.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSubcategories(Company $company, SupplierDepartment $supplierDepartment): array
    {
        $supplier = $this->requireSupplier($company);
        $supplierId = (int) $supplier->getKey();
        $departmentId = $this->ensureSupplierDepartmentBelongsToSupplier($supplierDepartment, $supplierId);

        $locale = app()->getLocale();
        $fallbackLocale = $this->fallbackLocale($locale);

        $subcategories = Subcategory::query()
            ->where('department_id', $departmentId)
            ->with('translations')
            ->orderBy('id')
            ->get();

        $subcategoryIds = $subcategories->pluck('id')->all();

        $familiesBySubcategory = $this->familiesBySubcategory($supplierId, $subcategoryIds);
        $supplierDepartmentSummary = $this->supplierDepartmentSummary($supplierDepartment, $locale, $fallbackLocale);

        return $subcategories->map(function (Subcategory $subcategory) use ($familiesBySubcategory, $supplierDepartmentSummary, $locale, $fallbackLocale): array {
            $subcategoryId = (int) $subcategory->getKey();

            return [
                'supplier_department' => $supplierDepartmentSummary,
                'id' => $subcategoryId,
                'name' => $this->translatedName($subcategory, $locale, $fallbackLocale),
                'families' => $familiesBySubcategory[$subcategoryId] ?? [],
            ];
        })->all();
    }

    private function supplierForCompany(Company $company): ?Supplier
    {
        return Supplier::query()
            ->where('company_id', $company->getKey())
            ->first();
    }

    private function requireSupplier(Company $company): Supplier
    {
        $supplier = $this->supplierForCompany($company);

        if ($supplier === null) {
            abort(404);
        }

        return $supplier;
    }

    private function ensureSupplierSolutionBelongsToSupplier(SupplierSolution $supplierSolution, int $supplierId): void
    {
        if ((int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }
    }

    private function ensureSupplierBrandBelongsToSupplier(SupplierBrand $supplierBrand, int $supplierId): void
    {
        $supplierBrand->loadMissing('supplierSolution');
        $supplierSolution = $supplierBrand->supplierSolution;

        if ($supplierSolution === null || (int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }
    }

    private function ensureSupplierDepartmentBelongsToSupplier(SupplierDepartment $supplierDepartment, int $supplierId): int
    {
        $supplierDepartment->loadMissing('supplierBrand.supplierSolution');
        $supplierSolution = optional($supplierDepartment->supplierBrand)->supplierSolution;

        if ($supplierSolution === null || (int) $supplierSolution->supplier_id !== $supplierId) {
            abort(404);
        }

        return (int) $supplierDepartment->department_id;
    }

    private function supplierDepartmentSummary(SupplierDepartment $supplierDepartment, string $locale, string $fallbackLocale): array
    {
        $supplierDepartment->loadMissing('department.translations', 'department.media');

        return [
            'id' => (int) $supplierDepartment->getKey(),
            'name' => $this->translatedName($supplierDepartment->department, $locale, $fallbackLocale),
        ];
    }

    /**
     * @param  array<int, int>  $subcategoryIds
     * @return array<int, array<int, array{id:int, name:string|null}>>
     */
    private function familiesBySubcategory(int $supplierId, array $subcategoryIds): array
    {
        if ($subcategoryIds === []) {
            return [];
        }

        return Family::query()
            ->where('supplier_id', $supplierId)
            ->whereIn('subcategory_id', $subcategoryIds)
            ->orderBy('name')
            ->get(['id', 'name', 'subcategory_id'])
            ->groupBy('subcategory_id')
            ->map(static function (Collection $group): array {
                return $group->map(static function (Family $family): array {
                    return [
                        'id' => (int) $family->getKey(),
                        'name' => $family->name,
                    ];
                })->values()->all();
            })
            ->toArray();
    }

    private function translatedName(?Model $model, string $locale, string $fallbackLocale): ?string
    {
        if ($model === null) {
            return null;
        }

        if (! method_exists($model, 'translate')) {
            return $model->getAttribute('name');
        }

        $translation = $model->translate($locale);

        if ($translation === null && $fallbackLocale !== $locale) {
            $translation = $model->translate($fallbackLocale);
        }

        return $translation?->name ?? $model->getAttribute('name');
    }

    private function fallbackLocale(string $locale): string
    {
        $fallback = app('translator')->getFallback();

        if (is_string($fallback) && $fallback !== '') {
            return $fallback;
        }

        return config('app.fallback_locale', $locale);
    }
}

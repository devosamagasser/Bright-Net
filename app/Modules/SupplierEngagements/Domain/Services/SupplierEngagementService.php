<?php

namespace App\Modules\SupplierEngagements\Domain\Services;

use App\Models\Supplier;
use App\Models\SupplierBrand;
use App\Models\SupplierSolution;
use App\Models\SupplierDepartment;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\Families\Application\UseCases\ListFamiliesUseCase;

class SupplierEngagementService
{
    /**
     * Retrieve the solutions associated with the given supplier company.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSolutions(Company $company): array
    {
        $supplier = $company->supplier;

        if ($supplier === null) {
            return [];
        }


        return $supplier->solutions()
            ->with('solution.translations')
            ->orderBy('id')
            ->get()
            ->map(function (SupplierSolution $supplierSolution): array {
                $solution = $supplierSolution->solution;

                return [
                    'supplier_solution_id' => (int) $supplierSolution->getKey(),
                    'solution' => [
                        'id' => $solution->id,
                        'name' => $solution->name,
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
        $supplier = $company->supplier;
        $this->ensureSupplierSolutionBelongsToSupplier($supplierSolution, (int) $supplier->getKey());

        return $supplierSolution->brands()
            ->with(['brand.media', 'brand.region'])
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
                    'region' => $brand?->region->name ?? null,
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
        $supplier = $company->supplier;
        $this->ensureSupplierBrandBelongsToSupplier($supplierBrand, (int) $supplier->getKey());

        return $supplierBrand->departments()
            ->with('department.media', 'department.translations')
            ->orderBy('supplier_departments.id')
            ->get()
            ->map(function (SupplierDepartment $supplierDepartment): array {
                $department = $supplierDepartment->department;

                return [
                    'supplier_department_id' => (int) $supplierDepartment->getKey(),
                    'id' => $department?->getKey(),
                    'name' => $department?->name,
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
    public function listSubcategories(Company $company, SupplierDepartment $supplierDepartment): Collection
    {
        $supplier = $company->supplier;
        $supplierId = (int) $supplier->getKey();
        $departmentId = $this->ensureSupplierDepartmentBelongsToSupplier($supplierDepartment, $supplierId);

        $subcategories = Subcategory::query()
            ->where('department_id', $departmentId)
            ->with(['families' => function ($query) use ($supplierId, $supplierDepartment) {
                $query->where('supplier_id', $supplierId)->orderBy('id', 'desc');
                $query->where('supplier_department_id', $supplierDepartment->getKey())->orderBy('id', 'desc');
            }, 'families.fieldValues.field'])
            ->orderBy('id')
            ->get();

        return $subcategories;
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

}

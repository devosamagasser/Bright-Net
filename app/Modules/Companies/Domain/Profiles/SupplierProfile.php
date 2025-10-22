<?php

namespace App\Modules\Companies\Domain\Profiles;

use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierProfile implements CompanyProfileInterface
{
    public function type(): CompanyType
    {
        return CompanyType::SUPPLIER;
    }

    public function relations(): array
    {
        return [];
    }

    public function create(Company $company, array $payload): void
    {
        $contact = $this->contactAttributes($payload);
        $solutions = $this->solutionsPayload($payload);

        $supplierId = $this->upsertSupplier($company, $contact);

        $this->syncSolutions($supplierId, $solutions);
    }

    public function update(Company $company, array $payload): void
    {
        $contact = $this->contactAttributes($payload);
        $solutions = $this->solutionsPayload($payload);

        $supplierId = $this->upsertSupplier($company, $contact);

        $this->syncSolutions($supplierId, $solutions);
    }

    public function serialize(Company $company): array
    {
        $supplier = $this->supplierRecord($company);

        if ($supplier === null) {
            return [
                'contact_email' => null,
                'contact_phone' => null,
                'website' => null,
                'solutions' => [],
            ];
        }

        return [
            'contact_email' => $supplier['contact_email'],
            'contact_phone' => $supplier['contact_phone'],
            'website' => $supplier['website'],
            'solutions' => $this->loadSupplierSolutions((int) $supplier['id']),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function contactAttributes(array $payload): array
    {
        return [
            'contact_email' => $payload['contact_email'] ?? null,
            'contact_phone' => $payload['contact_phone'] ?? null,
            'website' => $payload['website'] ?? null,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<int, array<string, mixed>>
     */
    private function solutionsPayload(array $payload): array
    {
        $solutions = Arr::get($payload, 'solutions', []);

        if (! is_array($solutions)) {
            return [];
        }

        $normalized = [];

        foreach ($solutions as $solution) {
            if (! is_array($solution) || ! isset($solution['solution_id'])) {
                continue;
            }

            $solutionId = (int) $solution['solution_id'];

            if ($solutionId <= 0) {
                continue;
            }

            $brandsPayload = [];

            if (isset($solution['brands']) && is_array($solution['brands'])) {
                foreach ($solution['brands'] as $brand) {
                    if (! is_array($brand) || ! isset($brand['brand_id'])) {
                        continue;
                    }

                    $brandId = (int) $brand['brand_id'];

                    if ($brandId <= 0) {
                        continue;
                    }

                    $brandsPayload[$brandId] = [
                        'brand_id' => $brandId,
                        'departments' => $this->uniqueIds($brand['departments'] ?? []),
                    ];
                }
            }

            $normalized[$solutionId] = [
                'solution_id' => $solutionId,
                'brands' => array_values($brandsPayload),
            ];
        }

        return array_values($normalized);
    }

    /**
     * @param  mixed  $values
     * @return array<int, int>
     */
    private function uniqueIds(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        return array_values(array_unique(array_map(static fn ($value) => (int) $value, $values)));
    }

    private function upsertSupplier(Company $company, array $attributes): int
    {
        $existing = DB::table('suppliers')
            ->select('id')
            ->where('company_id', $company->getKey())
            ->first();

        $now = now();

        if ($existing === null) {
            return (int) DB::table('suppliers')->insertGetId([
                'company_id' => $company->getKey(),
                'contact_email' => $attributes['contact_email'] ?? null,
                'contact_phone' => $attributes['contact_phone'] ?? null,
                'website' => $attributes['website'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('suppliers')
            ->where('id', $existing->id)
            ->update([
                'contact_email' => $attributes['contact_email'] ?? null,
                'contact_phone' => $attributes['contact_phone'] ?? null,
                'website' => $attributes['website'] ?? null,
                'updated_at' => $now,
            ]);

        return (int) $existing->id;
    }

    /**
     * @param  array<int, array<string, mixed>>  $solutions
     */
    private function syncSolutions(int $supplierId, array $solutions): void
    {
        if ($solutions === []) {
            DB::table('supplier_solutions')
                ->where('supplier_id', $supplierId)
                ->delete();

            return;
        }

        $solutionIds = array_values(array_unique(array_map(
            static fn (array $solution): int => (int) $solution['solution_id'],
            $solutions
        )));

        $solutionIds = array_values(array_filter(
            $solutionIds,
            static fn (int $solutionId): bool => $solutionId > 0
        ));

        if ($solutionIds === []) {
            DB::table('supplier_solutions')
                ->where('supplier_id', $supplierId)
                ->delete();

            return;
        }

        $now = now();

        $records = array_map(
            static function (int $solutionId) use ($supplierId, $now): array {
                return [
                    'supplier_id' => $supplierId,
                    'solution_id' => $solutionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            },
            $solutionIds
        );

        DB::table('supplier_solutions')->upsert(
            $records,
            ['supplier_id', 'solution_id'],
            ['updated_at']
        );

        $solutionRecords = DB::table('supplier_solutions')
            ->where('supplier_id', $supplierId)
            ->pluck('id', 'solution_id');

        $currentSolutions = $solutionRecords->only($solutionIds);

        foreach ($solutions as $solution) {
            $solutionId = (int) $solution['solution_id'];
            $supplierSolutionId = $currentSolutions->get($solutionId);

            if ($supplierSolutionId === null) {
                continue;
            }

            $this->syncBrands((int) $supplierSolutionId, $solution['brands'] ?? []);
        }

        $removedSolutions = $solutionRecords->except($solutionIds)->values();

        if ($removedSolutions->isNotEmpty()) {
            DB::table('supplier_solutions')
                ->whereIn('id', $removedSolutions->all())
                ->delete();
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $brands
     */
    private function syncBrands(int $supplierSolutionId, array $brands): void
    {
        $brandMap = [];

        foreach ($brands as $brand) {
            if (! is_array($brand) || ! isset($brand['brand_id'])) {
                continue;
            }

            $brandId = (int) $brand['brand_id'];

            if ($brandId <= 0) {
                continue;
            }

            $departments = $this->uniqueIds($brand['departments'] ?? []);

            if (! array_key_exists($brandId, $brandMap)) {
                $brandMap[$brandId] = $departments;
                continue;
            }

            $brandMap[$brandId] = $this->uniqueIds(array_merge($brandMap[$brandId], $departments));
        }

        if ($brandMap === []) {
            $brandIds = DB::table('supplier_brands')
                ->where('supplier_solution_id', $supplierSolutionId)
                ->pluck('id');

            if ($brandIds->isNotEmpty()) {
                DB::table('supplier_departments')
                    ->whereIn('supplier_brand_id', $brandIds->all())
                    ->delete();
            }

            DB::table('supplier_brands')
                ->where('supplier_solution_id', $supplierSolutionId)
                ->delete();

            return;
        }

        $now = now();
        $brandIds = array_keys($brandMap);

        $records = array_map(
            static function (int $brandId) use ($supplierSolutionId, $now): array {
                return [
                    'supplier_solution_id' => $supplierSolutionId,
                    'brand_id' => $brandId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            },
            $brandIds
        );

        DB::table('supplier_brands')->upsert(
            $records,
            ['supplier_solution_id', 'brand_id'],
            ['updated_at']
        );

        $brandRecords = DB::table('supplier_brands')
            ->where('supplier_solution_id', $supplierSolutionId)
            ->pluck('id', 'brand_id');

        $currentBrands = $brandRecords->only($brandIds);

        foreach ($brandMap as $brandId => $departments) {
            $supplierBrandId = $currentBrands->get($brandId);

            if ($supplierBrandId === null) {
                continue;
            }

            $this->syncDepartments((int) $supplierBrandId, $departments);
        }

        $removedBrands = $brandRecords->except($brandIds)->values();

        if ($removedBrands->isNotEmpty()) {
            DB::table('supplier_departments')
                ->whereIn('supplier_brand_id', $removedBrands->all())
                ->delete();

            DB::table('supplier_brands')
                ->whereIn('id', $removedBrands->all())
                ->delete();
        }
    }

    /**
     * @param  array<int, int>  $departmentIds
     */
    private function syncDepartments(int $supplierBrandId, array $departmentIds): void
    {
        $departmentIds = $this->uniqueIds($departmentIds);

        if ($departmentIds === []) {
            DB::table('supplier_departments')
                ->where('supplier_brand_id', $supplierBrandId)
                ->delete();

            return;
        }

        $now = now();

        $records = array_map(
            static function (int $departmentId) use ($supplierBrandId, $now): array {
                return [
                    'supplier_brand_id' => $supplierBrandId,
                    'department_id' => $departmentId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            },
            $departmentIds
        );

        DB::table('supplier_departments')->upsert(
            $records,
            ['supplier_brand_id', 'department_id'],
            ['updated_at']
        );

        DB::table('supplier_departments')
            ->where('supplier_brand_id', $supplierBrandId)
            ->whereNotIn('department_id', $departmentIds)
            ->delete();
    }

    private function supplierRecord(Company $company): ?array
    {
        $record = DB::table('suppliers')
            ->where('company_id', $company->getKey())
            ->first();

        if ($record === null) {
            return null;
        }

        return [
            'id' => (int) $record->id,
            'contact_email' => $record->contact_email,
            'contact_phone' => $record->contact_phone,
            'website' => $record->website,
        ];
    }

    private function loadSupplierSolutions(int $supplierId): array
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', $locale);

        /** @var Collection<int, object> $solutions */
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

        if ($solutions->isEmpty()) {
            return [];
        }

        $supplierSolutionIds = $solutions->pluck('supplier_solution_id')->all();

        /** @var Collection<int, object> $brands */
        $brands = DB::table('supplier_brands as sb')
            ->join('brands as b', 'b.id', '=', 'sb.brand_id')
            ->leftJoinSub($this->brandLogoSubquery(), 'brand_logos', function ($join): void {
                $join->on('brand_logos.brand_id', '=', 'b.id');
            })
            ->leftJoin('media as m', 'm.id', '=', 'brand_logos.media_id')
            ->whereIn('sb.supplier_solution_id', $supplierSolutionIds)
            ->select([
                'sb.id as supplier_brand_id',
                'sb.supplier_solution_id',
                'sb.brand_id',
                'b.name as brand_name',
                'm.id as media_id',
                'm.disk as media_disk',
                'm.file_name as media_file_name',
            ])
            ->orderBy('b.name')
            ->get();

        $supplierBrandIds = $brands->pluck('supplier_brand_id')->all();

        /** @var Collection<int, object> $departments */
        $departments = DB::table('supplier_departments as sd')
            ->join('departments as d', 'd.id', '=', 'sd.department_id')
            ->leftJoin('department_translations as dt', function ($join) use ($locale): void {
                $join->on('dt.department_id', '=', 'd.id')
                    ->where('dt.locale', '=', $locale);
            })
            ->leftJoin('department_translations as fdt', function ($join) use ($fallbackLocale): void {
                $join->on('fdt.department_id', '=', 'd.id')
                    ->where('fdt.locale', '=', $fallbackLocale);
            })
            ->whereIn('sd.supplier_brand_id', $supplierBrandIds)
            ->select([
                'sd.id as supplier_department_id',
                'sd.supplier_brand_id',
                'sd.department_id',
                DB::raw('COALESCE(dt.name, fdt.name) as department_name'),
            ])
            ->orderBy('sd.id')
            ->get();

        $departmentIds = $departments->pluck('department_id')->unique()->values();

        $subcategories = collect();

        if ($departmentIds->isNotEmpty()) {
            /** @var Collection<int, object> $subcategories */
            $subcategories = DB::table('subcategories as sc')
                ->leftJoin('subcategory_translations as sct', function ($join) use ($locale): void {
                    $join->on('sct.subcategory_id', '=', 'sc.id')
                        ->where('sct.locale', '=', $locale);
                })
                ->leftJoin('subcategory_translations as fsct', function ($join) use ($fallbackLocale): void {
                    $join->on('fsct.subcategory_id', '=', 'sc.id')
                        ->where('fsct.locale', '=', $fallbackLocale);
                })
                ->whereIn('sc.department_id', $departmentIds->all())
                ->select([
                    'sc.department_id',
                    'sc.id as subcategory_id',
                    DB::raw('COALESCE(sct.name, fsct.name) as subcategory_name'),
                ])
                ->orderBy('sc.id')
                ->get();
        }

        $subcategoriesByDepartment = $subcategories
            ->groupBy('department_id')
            ->map(static function (Collection $items): array {
                return $items
                    ->map(static function (object $subcategory): array {
                        return [
                            'id' => (int) $subcategory->subcategory_id,
                            'name' => $subcategory->subcategory_name,
                        ];
                    })
                    ->values()
                    ->all();
            });

        $departmentsByBrand = $departments
            ->groupBy('supplier_brand_id')
            ->map(function (Collection $items) use ($subcategoriesByDepartment): array {
                return $items
                    ->map(function (object $department) use ($subcategoriesByDepartment): array {
                        $departmentId = (int) $department->department_id;

                        return [
                            'supplier_department_id' => (int) $department->supplier_department_id,
                            'id' => $departmentId,
                            'name' => $department->department_name,
                            'subcategories' => $subcategoriesByDepartment->get($departmentId, []),
                        ];
                    })
                    ->values()
                    ->all();
            });

        $brandsBySolution = $brands
            ->groupBy('supplier_solution_id')
            ->map(function (Collection $items) use ($departmentsByBrand): array {
                return $items
                    ->map(function (object $brand) use ($departmentsByBrand): array {
                        $supplierBrandId = (int) $brand->supplier_brand_id;

                        return [
                            'supplier_brand_id' => $supplierBrandId,
                            'id' => (int) $brand->brand_id,
                            'name' => $brand->brand_name,
                            'logo' => $this->mediaUrl($brand->media_disk, $brand->media_id, $brand->media_file_name),
                            'departments' => $departmentsByBrand->get($supplierBrandId, []),
                        ];
                    })
                    ->values()
                    ->all();
            });

        return $solutions
            ->map(function (object $solution) use ($brandsBySolution): array {
                $supplierSolutionId = (int) $solution->supplier_solution_id;

                return [
                    'supplier_solution_id' => $supplierSolutionId,
                    'solution_id' => (int) $solution->solution_id,
                    'solution' => [
                        'id' => (int) $solution->solution_id,
                        'name' => $solution->solution_name,
                    ],
                    'brands' => $brandsBySolution->get($supplierSolutionId, []),
                ];
            })
            ->values()
            ->all();
    }

    private function brandLogoSubquery(): Builder
    {
        return DB::table('media')
            ->selectRaw('MAX(id) as media_id, model_id as brand_id')
            ->where('model_type', '=', Brand::class)
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

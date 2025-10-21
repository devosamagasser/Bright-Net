<?php

namespace App\Modules\Companies\Domain\Profiles;

use App\Models\Supplier;
use App\Models\SupplierSolution;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Support\Arr;

class SupplierProfile implements CompanyProfileInterface
{
    public function type(): CompanyType
    {
        return CompanyType::SUPPLIER;
    }

    public function relations(): array
    {
        return [
            'supplier',
            'supplier.supplierSolutions',
            'supplier.supplierSolutions.solution.translations',
            'supplier.supplierSolutions.departments.translations',
            'supplier.supplierSolutions.brands',
            'supplier.supplierSolutions.brands.media',
        ];
    }

    public function create(Company $company, array $payload): void
    {
        /** @var Supplier $supplier */
        $supplier = $company->supplier()->updateOrCreate([], $this->contactAttributes($payload));

        $this->syncSolutions($supplier, $this->solutionsPayload($payload));
    }

    public function update(Company $company, array $payload): void
    {
        /** @var Supplier|null $supplier */
        $supplier = $company->supplier;

        if ($supplier === null) {
            /** @var Supplier $supplier */
            $supplier = $company->supplier()->create($this->contactAttributes($payload));
        } else {
            $supplier->fill($this->contactAttributes($payload));
            $supplier->save();
        }

        $this->syncSolutions($supplier, $this->solutionsPayload($payload));
    }

    public function serialize(Company $company): array
    {
        /** @var Supplier|null $supplier */
        $supplier = $company->relationLoaded('supplier')
            ? $company->supplier
            : $company->supplier()->first();

        $solutions = $supplier?->relationLoaded('supplierSolutions')
            ? $supplier->supplierSolutions
            : $supplier?->supplierSolutions()->with([
                'solution.translations',
                'departments.translations',
                'brands.media',
            ])->get();

        return [
            'contact_email' => $supplier?->contact_email,
            'contact_phone' => $supplier?->contact_phone,
            'website' => $supplier?->website,
            'solutions' => $solutions?->map(static function (SupplierSolution $supplierSolution): array {
                $solution = $supplierSolution->solution;

                return [
                    'supplier_solution_id' => $supplierSolution->getKey(),
                    'solution_id' => $supplierSolution->solution_id,
                    'solution' => $solution ? [
                        'id' => $solution->getKey(),
                        'name' => $solution->name,
                    ] : null,
                    'departments' => $supplierSolution->departments
                        ->map(static fn ($department) => [
                            'id' => $department->getKey(),
                            'name' => $department->name,
                        ])
                        ->values()
                        ->toArray(),
                    'brands' => $supplierSolution->brands
                        ->map(static fn ($brand) => [
                            'id' => $brand->getKey(),
                            'name' => $brand->name,
                            'logo' => $brand->getFirstMediaUrl('logo') ?: null,
                        ])
                        ->values()
                        ->toArray(),
                ];
            })->values()->toArray() ?? [],
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

            $normalized[$solutionId] = [
                'solution_id' => $solutionId,
                'departments' => $this->uniqueIds($solution['departments'] ?? []),
                'brands' => $this->uniqueIds($solution['brands'] ?? []),
            ];
        }

        return array_values($normalized);
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<int, int>
     */
    private function uniqueIds(array $values): array
    {
        return array_values(array_unique(array_map(static fn ($value) => (int) $value, $values)));
    }

    /**
     * @param  array<int, array<string, mixed>>  $solutions
     */
    private function syncSolutions(Supplier $supplier, array $solutions): void
    {
        if ($solutions === []) {
            $supplier->supplierSolutions()->each(static function (SupplierSolution $supplierSolution): void {
                $supplierSolution->delete();
            });

            return;
        }

        $retainedIds = [];

        foreach ($solutions as $solution) {
            $supplierSolution = $supplier->supplierSolutions()->firstOrCreate([
                'solution_id' => $solution['solution_id'],
            ]);

            $retainedIds[] = $supplierSolution->getKey();

            $supplierSolution->departments()->sync($solution['departments']);
            $supplierSolution->brands()->sync($solution['brands']);
        }

        $supplier->supplierSolutions()
            ->whereNotIn('id', $retainedIds)
            ->get()
            ->each(static function (SupplierSolution $supplierSolution): void {
                $supplierSolution->delete();
            });
    }
}

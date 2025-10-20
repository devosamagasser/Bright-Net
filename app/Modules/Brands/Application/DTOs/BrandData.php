<?php

namespace App\Modules\Brands\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Brands\Domain\Models\Brand;

class BrandData
{
    /**
     * @param  array<string, mixed>|null  $region
     * @param  array<int, int>  $solutionIds
     * @param  array<int, int>  $departmentIds
     * @param  array<int, array<string, mixed>>  $solutions
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $regionId,
        public readonly ?array $region,
        public readonly array $solutionIds,
        public readonly array $departmentIds,
        public readonly array $solutions,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Brand $brand): self
    {
        $brand->loadMissing(['region', 'solutions', 'departments']);

        $solutionIds = $brand->solutions->pluck('id')->map(fn ($id) => (int) $id)->all();

        $departments = $brand->departments;
        $departmentIds = $departments->pluck('id')->map(fn ($id) => (int) $id)->all();

        $departmentsBySolution = $departments->groupBy('solution_id');

        $solutions = $brand->solutions->map(function ($solution) use ($departmentsBySolution) {
            $solutionDepartments = $departmentsBySolution->get($solution->getKey(), collect());

            return [
                'id' => $solution->getKey(),
                'name' => $solution->name,
                'departments' => Collection::make($solutionDepartments)
                    ->map(static fn ($department) => [
                        'id' => $department->getKey(),
                        'name' => $department->name,
                    ])
                    ->values()
                    ->all(),
            ];
        })->values()->all();

        return new self(
            id: $brand->getKey(),
            name: $brand->name,
            regionId: (int) $brand->region_id,
            region: $brand->region ? [
                'id' => $brand->region->getKey(),
                'name' => $brand->region->name,
            ] : null,
            solutionIds: $solutionIds,
            departmentIds: $departmentIds,
            solutions: $solutions,
            createdAt: $brand->created_at?->toISOString() ?? '',
            updatedAt: $brand->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  Collection<int, Brand>  $brands
     * @return Collection<int, self>
     */
    public static function collection(Collection $brands): Collection
    {
        return $brands->map(static fn (Brand $brand) => self::fromModel($brand));
    }
}

<?php

namespace App\Modules\SolutionsCatalog\Application\DTOs;

use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Support\Collection;

class SolutionBrandData
{
    /**
     * @param  array<int, array{id:int,name:string}>  $departments
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $logo,
        public readonly ?array $region,
        public readonly array $departments,
    ) {
    }

    public static function fromModel(Brand $brand): self
    {
        return new self(
            id: $brand->getKey(),
            name: $brand->name,
            logo: $brand->getFirstMediaUrl('logo') ?: null,
            region: $brand->region ? [
                'id' => $brand->region->getKey(),
                'name' => $brand->region->name,
            ] : null,
            departments: $brand->departments
                ->map(static fn ($department) => [
                    'id' => (int) $department->getKey(),
                    'name' => $department->name,
                ])
                ->values()
                ->all(),
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

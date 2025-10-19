<?php

namespace App\Modules\Geography\Application\DTOs;

use App\Modules\Geography\Domain\Models\Region;
use Illuminate\Support\Collection;

class RegionData
{
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Region $region): self
    {
        return new self(
            id: $region->getKey(),
            name: (string) $region->name,
            createdAt: $region->created_at?->toISOString() ?? '',
            updatedAt: $region->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Region>  $regions
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function collection(Collection $regions): Collection
    {
        return $regions->map(static fn (Region $region) => self::fromModel($region));
    }
}

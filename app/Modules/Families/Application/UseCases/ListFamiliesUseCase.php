<?php

namespace App\Modules\Families\Application\UseCases;

use App\Modules\Families\Application\DTOs\FamilyData;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use Illuminate\Support\Collection;

class ListFamiliesUseCase
{
    public function __construct(private readonly FamilyRepositoryInterface $families)
    {
    }

    /**
     * @return Collection<int, FamilyData>
     */
    public function handle(int $subcategoryId, ?int $supplierId = null): Collection
    {
        $families = $this->families->getBySubcategory($subcategoryId, $supplierId);

        return FamilyData::collection($families);
    }
}

<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Application\DTOs\ProductGroupData;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductGroupsUseCase
{
    public function __construct(
        private readonly ProductGroupRepositoryInterface $groups,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function handle(int $familyId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator
    {
        $paginator = $this->groups->paginateByFamily($familyId, $perPage, $supplierId);

        // Get first product for each group
        $groupIds = $paginator->getCollection()->pluck('id')->toArray();
        $firstProducts = collect();

        if (!empty($groupIds)) {
            $firstProducts = $this->products->getByGroups($groupIds, $supplierId);
        }

        $paginator->setCollection(
            ProductGroupData::collection($paginator->getCollection(), $firstProducts)
        );

        return $paginator;
    }
}


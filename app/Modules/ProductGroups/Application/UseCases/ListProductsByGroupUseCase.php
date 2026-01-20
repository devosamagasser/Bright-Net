<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsByGroupUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly ProductGroupRepositoryInterface $groups,
        private readonly FamilyRepositoryInterface $families
    ) {
    }

    /**
     * @return array{products: LengthAwarePaginator, roots: array}
     */
    public function handle(int $groupId, int $perPage = 15, ?int $supplierId = null): array
    {
        $group = $this->groups->find($groupId);
        
        if ($group === null) {
            return [
                'products' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(),
                    0,
                    $perPage,
                    1
                ),
                'roots' => [],
            ];
        }

        // Get products by group using repository with pagination
        $paginator = $this->products->paginateByGroup($groupId, $perPage, $supplierId);

        $family = $paginator->getCollection()->first()?->family ?? $this->families->find($group->family_id);

        $paginator->setCollection(
            ProductData::collection($paginator->getCollection())
        );

        return [
            'products' => $paginator,
            'roots' => $family ? ProductData::serializeRoots($family) : [],
        ];
    }
}


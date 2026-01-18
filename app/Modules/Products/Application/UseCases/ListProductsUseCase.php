<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly FamilyRepositoryInterface $families
    )
    {
    }

    /**
     * @return array{products: LengthAwarePaginator, roots: array}
     */
    public function handle(int $familyId, int $perPage = 15, ?int $supplierId = null): array
    {
        $paginator = $this->products->paginateByFamily($familyId, $perPage, $supplierId);
        $family = $paginator->getCollection()->first()?->family ?? $this->families->find($familyId);

        $paginator->setCollection(
            ProductData::collection($paginator->getCollection())
        );

        return [
            'products' => $paginator,
            'roots' => $family ? ProductData::serializeRoots($family) : [],
        ];
    }

}

<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\Products\Domain\Services\ProductPriceService;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Products\Domain\Services\ProductAccessoryService;
use App\Modules\Products\Application\DTOs\{ProductData, ProductInput};
use App\Modules\Products\Domain\Services\ProductFieldValueSyncService;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class CalculateBudgetPriceUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private readonly ProductPriceService $productPriceService,
    ) {
    }

    public function handle(array $data, int $brandId)
    {
        $product = $this->productRepository->findWhere([
            ['code',$data['code']],
            ['supplier_brand_id', $brandId],
        ], relations: ['prices']);
        return $this->productPriceService->calculateBudgetPrice($product, $data['quantity']);
    }

}

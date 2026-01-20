<?php

namespace App\Modules\Products\Presentation\Http\Controllers;

use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Presentation\Resources\ProductResource;
use App\Modules\Products\Presentation\Http\Requests\StoreProductAccessoryRequest;
use App\Modules\Products\Application\UseCases\AddAccessoryToProductUseCase;
use App\Modules\Shared\Support\Helper\ApiResponse;

class ProductAccessoryController
{
    public function __construct(private readonly AddAccessoryToProductUseCase $addAccessory)
    {
    }

    public function store(StoreProductAccessoryRequest $request, Product $product)
    {
        $productData = $this->addAccessory->handle(
            $product,
            $request->payload(),
            $this->authenticatedSupplierId()
        );

        return ApiResponse::created(
            ProductResource::make($productData)->resolve()
        );
    }

    private function authenticatedSupplierId(): ?int
    {
        return auth()->user()?->company?->supplier?->id;
    }
}

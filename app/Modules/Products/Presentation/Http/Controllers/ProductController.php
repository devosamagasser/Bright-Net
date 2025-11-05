<?php

namespace App\Modules\Products\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Products\Presentation\Resources\{ProductResource, ProductDataSheetResource};
use App\Modules\Products\Presentation\Http\Requests\{StoreProductRequest, UpdateProductRequest};
use App\Modules\Products\Application\UseCases\{
    CreateProductUseCase,
    DeleteProductUseCase,
    ListProductsUseCase,
    ShowProductUseCase,
    ShowDataSheetUseCase,
    UpdateProductUseCase,
};
use App\Modules\Products\Application\DTOs\ProductInput;
use App\Modules\Products\Domain\Models\Product;

class ProductController
{
    public function __construct(
        private readonly CreateProductUseCase $createProduct,
        private readonly UpdateProductUseCase $updateProduct,
        private readonly DeleteProductUseCase $deleteProduct,
        private readonly ShowProductUseCase $showProduct,
        private readonly ListProductsUseCase $listProducts,
    ) {
    }

    public function index(Request $request, int $family)
    {
        $supplierId = $this->authenticatedSupplierId() ?? $request->query('supplier_id');
        $products = $this->listProducts->handle($family, $supplierId !== null ? (int) $supplierId : null);
        return ApiResponse::success(
            ProductResource::collection($products)->resolve()
        );
    }

    public function store(StoreProductRequest $request)
    {
        $input = ProductInput::fromArray(
            $request->all() + [
                'supplier_id' => $this->authenticatedSupplierId(),
            ]
        );

        $product = $this->createProduct->handle($input);

        return ApiResponse::created(
            ProductResource::make($product)->resolve()
        );
    }

    public function show(Product $product)
    {
        $productData = $this->showProduct->handle((int) $product->getKey());

        return ApiResponse::success(
            ProductResource::make($productData)->resolve()
        );
    }

    public function showDataSheet(Product $product)
    {
        $productData = $this->showProduct->handle((int) $product->getKey());

        return ApiResponse::success(
            ProductDataSheetResource::make($productData)->resolve()
        );
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $input = ProductInput::fromArray(
            $request->all() + [
                'supplier_id' => $this->authenticatedSupplierId(),
            ]
        );
        $productData = $this->updateProduct->handle($product, $input);

        return ApiResponse::updated(
            ProductResource::make($productData)->resolve()
        );
    }

    public function destroy(Product $product)
    {
        $this->deleteProduct->handle($product);

        return ApiResponse::deleted();
    }

    private function authenticatedSupplierId(): ?int
    {
        return auth()->user()?->company?->supplier?->id;
    }
}

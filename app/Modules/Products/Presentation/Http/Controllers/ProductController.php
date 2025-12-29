<?php

namespace App\Modules\Products\Presentation\Http\Controllers;

use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Products\Presentation\Resources\{ProductResource, ProductDataSheetResource, AllProductResource};
use App\Modules\Products\Presentation\Http\Requests\{
    StoreProductRequest,
    UpdateProductRequest,
    CutPasteProductRequest
};
use App\Modules\Products\Application\UseCases\{
    CreateProductUseCase,
    DeleteProductUseCase,
    ListProductsUseCase,
    ShowProductUseCase,
    UpdateProductUseCase,
    CutPasteProductsUseCase,
    ProductExportUseCase,
    ProductImportUseCase,
    CalculateBudgetPriceUseCase
};
use App\Modules\Products\Application\DTOs\ProductInput;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Families\Domain\Models\Family;
use Maatwebsite\Excel\Facades\Excel;
class ProductController
{
    public function __construct(
        private readonly CreateProductUseCase $createProduct,
        private readonly UpdateProductUseCase $updateProduct,
        private readonly DeleteProductUseCase $deleteProduct,
        private readonly ShowProductUseCase $showProduct,
        private readonly ListProductsUseCase $listProducts,
        private readonly CutPasteProductsUseCase $cutPasteProducts,
        private readonly CalculateBudgetPriceUseCase $calculateBudgetPrice,
    ) {
    }

    public function index(Request $request, int $family)
    {
        $supplierId = $this->authenticatedSupplierId() ?? $request->query('supplier_id');
        $data = $this->listProducts->handle($family, $supplierId !== null ? (int) $supplierId : null);
        $collection = ProductResource::collection($data['products'])
            ->additional(['roots' => $data['roots']])
            ->response()
            ->getData(true);
        return ApiResponse::success($collection);
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

    public function paste(CutPasteProductRequest $request, Product $product)
    {
        $destinationFamilyId = $request->input('family_id');
        $productData =$this->cutPasteProducts->handle($product, (int) $destinationFamilyId);

        return ApiResponse::updated(
            ProductResource::make($productData)->resolve()
        );
    }

    public function export(Family $family)
    {
        return Excel::download(new ProductExportUseCase(), $family->name.'\'s_products_template.xlsx');
    }

    public function import(Request $request, Family $family, ProductRepositoryInterface $productRepositoryInterface)
    {
        $file = $request->file('file');
        Excel::import(
            new ProductImportUseCase(
                $family,
                $productRepositoryInterface
            ),
            $file
        );

        return ApiResponse::message('Products imported successfully');
    }

    public function budgetPrice(Request $request, Product $product)
    {
        $data = $request->validate(['quantity'=>'required|integer|min:1']);
        $price = $this->calculateBudgetPrice->handle($product, $data['quantity']);
        return ApiResponse::success([
            'price' => $price
        ]);
    }

    private function authenticatedSupplierId(): ?int
    {
        return auth()->user()?->company?->supplier?->id;
    }
}

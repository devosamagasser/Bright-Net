<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Products\Application\DTOs\{ProductData, ProductInput};
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Families\Domain\Models\Family;

class CutPasteProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(Product $product, int $family_id)
    {

        $family = $this->requireFamily((int) ($family_id ?? 0));
        $this->assertFamilyBelongsToSubcategory($family, $product);

        $updatedProduct = $this->products->cutPasteProduct(
            $product,
            $family_id
        );

        return collect([
            'product' => ProductData::fromModel($product),
            'roots' => ProductData::serializeRoots($family),
        ]);
    }

    private function requireFamily(int $familyId): Family
    {
        $family = Family::query()->find($familyId);

        if ($family === null) {
            throw ValidationException::withMessages([
                'family_id' => trans('validation.exists', ['attribute' => 'family']),
            ]);
        }

        return $family;
    }

    private function assertFamilyBelongsToSubcategory(Family $family, Product $product): void
    {
        $originalFamily = $product->family;

        if ($originalFamily->subcategory_id !== $family->subcategory_id) {
            throw ValidationException::withMessages([
                'family_id' => trans('validation.in', ['attribute' => 'family']),
            ]);
        }
    }
}

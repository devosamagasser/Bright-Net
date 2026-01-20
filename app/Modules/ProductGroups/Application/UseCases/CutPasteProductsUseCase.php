<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Application\DTOs\{ProductData, ProductInput};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;

class CutPasteProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly ProductGroupRepositoryInterface $groups,
    ) {
    }

    public function handle(Product $product, int $family_id, ?int $group_id = null)
    {
        $family = $this->requireFamily((int) ($family_id ?? 0));
        $this->assertFamilyBelongsToSubcategory($family, $product);

        $updatedProduct = DB::transaction(function () use ($product, $family_id, $group_id, $family) {
            $product->family_id = $family_id;
            
            // Handle group_id if provided
            if ($group_id !== null) {
                $group = $this->groups->find($group_id);
                if ($group !== null && $group->family_id === $family_id) {
                    $product->product_group_id = $group_id;
                } else {
                    // Create new group or use existing
                    $group = $this->groups->firstOrcreate(null, [
                        'family_id' => $family_id,
                        'supplier_id' => $family->supplier_id,
                        'subcategory_id' => $family->subcategory_id,
                    ]);
                    $product->product_group_id = $group->id;
                }
            }
            
            $product->save();
            return $this->products->cutPasteProduct($product, $family_id);
        });

        return ProductData::fromModel($updatedProduct, $family);
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

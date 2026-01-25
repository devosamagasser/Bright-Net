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
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class UpdateProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly ProductGroupRepositoryInterface $groups,
        private readonly DataTemplateRepositoryInterface $templates,
        private readonly ProductFieldValueSyncService $fieldValueSync,
        private readonly ProductAccessoryService $productAccessoryService,
        private readonly ProductPriceService $productPriceService,
    ) {
    }

    public function handle(Product $product, ProductInput $input)
    {
        $attributes = $input->attributes;

        $family = $this->requireFamily((int) ($attributes['family_id'] ?? 0));
        $this->assertFamilyBelongsToSupplier($family, $input->supplierId);

        $template = $this->templates->findBySubcategoryAndType(
            subcategoryId: $family->subcategory_id,
            type: DataTemplateType::PRODUCT
        );

        if ($template !== null) {
            $this->assertTemplateMatchesFamily($template, $family);

            $attributes = $attributes + [
                'data_template_id' => $template->id,
            ];
        }

        $updatedProduct = DB::transaction(function () use ($product, $attributes, $input, $family, &$updatedProduct) {
            // Handle group_id if provided
            if (isset($attributes['group_id'])) {
                $groupId = $attributes['group_id'] !== null ? (int) $attributes['group_id'] : null;
                $group = $this->groups->firstOrcreate($groupId, array_merge($attributes, ['family_id' => $family->id]));
                $attributes['product_group_id'] = $group->id;
                unset($attributes['group_id']);
            }

            // Update roots if family_id changed
            $familyChanged = isset($attributes['family_id']) && (int) $attributes['family_id'] !== (int) $product->family_id;
            if ($familyChanged && $template !== null) {
                $attributes = $attributes + [
                    'supplier_id'=> $family->supplier_id,
                    'solution_id'=> $family->subcategory->department->solution_id,
                    'supplier_solution_id'=> $family->department->supplierBrand->supplier_solution_id,
                    'brand_id'=> $family->department->supplierBrand->brand_id,
                    'supplier_brand_id'=> $family->department->supplier_brand_id,
                    'department_id'=> $family->department->department_id,
                    'supplier_department_id'=> $family->department->id,
                    'subcategory_id'=> $family->subcategory_id,
                ];
            }

            $updatedProduct = $this->products->update(
                product: $product,
                attributes: $attributes,
                translations: $input->translations,
                media: $input->media
            );

            $this->fieldValueSync->syncFieldValues($template ?? null, $product, $input->values);
            $this->productAccessoryService->syncAccessories($product, $input->accessories);
            $this->productPriceService->syncPrices($product, $input->prices);
            return $updatedProduct;
        });

        return ProductData::fromModel($updatedProduct,true);
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

    private function assertFamilyBelongsToSupplier(Family $family, ?int $supplierId): void
    {
        if ($supplierId === null) {
            return;
        }

        if ((int) $family->supplier_id !== $supplierId) {
            throw ValidationException::withMessages([
                'family_id' => trans('validation.in', ['attribute' => 'family']),
            ]);
        }
    }

    private function assertTemplateMatchesFamily(DataTemplate $template, Family $family): void
    {
        if ((int) $template->subcategory_id !== (int) $family->subcategory_id) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.in', ['attribute' => 'data template']),
            ]);
        }
    }
}

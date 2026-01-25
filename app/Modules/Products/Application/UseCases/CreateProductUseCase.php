<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use Illuminate\Validation\ValidationException;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\Products\Domain\Services\ProductPriceService;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Products\Domain\Services\ProductAccessoryService;
use App\Modules\Products\Application\DTOs\{ProductData, ProductInput};
use App\Modules\Products\Domain\Services\ProductFieldValueSyncService;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class CreateProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly ProductGroupRepositoryInterface $groups,
        private readonly DataTemplateRepositoryInterface $templates,
        private readonly ProductFieldValueSyncService $fieldValueSync,
        private readonly ProductAccessoryService $productAccessoryService,
        private readonly ProductPriceService $productPriceService,
        private readonly FamilyRepositoryInterface $family,
    ) {
    }

    public function handle(ProductInput $input)
    {
        $attributes = $input->attributes;

        $family = $this->requireFamily((int) ($attributes['family_id'] ?? 0));
        $this->assertFamilyBelongsToSupplier($family, $input->supplierId);

        $template = $this->templates->findBySubcategoryAndType(
                        $family->subcategory_id,
                        DataTemplateType::PRODUCT
                    );

        if ($template !== null) {
            $this->assertTemplateMatchesFamily($template, $family);
            $attributes = $attributes + [
                'data_template_id' => $template->id,
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

        $product = DB::transaction(function () use ($attributes, $input, &$product) {

            $groupId = isset($attributes['group_id']) ? (int) $attributes['group_id'] : null;
            $group = $this->groups->firstOrcreate($groupId, $attributes);
            $attributes['product_group_id'] = $group->id;
            unset($attributes['group_id']);

            $product = $this->products->create(
                $attributes,
                $input->translations,
                $input->media
            );

            $this->fieldValueSync->syncFieldValues($template ?? null, $product, $input->values);
            $this->productAccessoryService->syncAccessories($product, $input->accessories);
            $this->productPriceService->syncPrices($product, $input->prices);
            return $product;
        });

        return ProductData::fromModel($product, true);
    }

    private function requireFamily(int $familyId): Family
    {
        $family = $this->family->find($familyId);

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
        if ((int) $template->subcategory_id !== $family->subcategory_id) {

            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.in', ['attribute' => 'data template']),
            ]);
        }
    }
}

<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Products\Application\DTOs\{ProductData, ProductInput};
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\DataSheets\Domain\Models\DataTemplate;

class UpdateProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
        private readonly DataTemplateRepositoryInterface $templates,
    ) {
    }

    public function handle(Product $product, ProductInput $input): ProductData
    {
        $attributes = $input->attributes;

        $family = $this->requireFamily((int) ($attributes['family_id'] ?? 0));
        $this->assertFamilyBelongsToSupplier($family, $input->supplierId);

        $template = $this->requireTemplate($family );
        $this->assertTemplateMatchesFamily($template, $family);

        $attributes = $attributes + [
            'data_template_id' => $template->id,
        ];

        $updatedProduct = $this->products->update(
            $product,
            $attributes,
            $input->translations,
            $input->values,
            [
                'prices' => $input->prices,
                'sync_prices' => $input->shouldSyncPrices,
                'accessories' => $input->accessories,
                'sync_accessories' => $input->shouldSyncAccessories,
                'media' => $input->media,
            ]
        );

        return ProductData::fromModel($updatedProduct);
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

    private function requireTemplate(Family $family): DataTemplate
    {
        $template = $this->templates->findBySubcategoryAndType(
            $family->subcategory_id,
            DataTemplateType::PRODUCT
        );

        if ($template === null) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.exists', ['attribute' => 'data template']),
            ]);
        }

        return $template;
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

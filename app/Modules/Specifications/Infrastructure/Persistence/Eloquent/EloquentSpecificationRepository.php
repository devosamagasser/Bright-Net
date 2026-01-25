<?php

namespace App\Modules\Specifications\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\Specifications\Domain\Services\{
    SpecificationAccessoryService,
    SpecificationNumberingService
};
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class EloquentSpecificationRepository implements SpecificationRepositoryInterface
{
    public function __construct(
        private readonly SpecificationAccessoryService $accessoryService,
        private readonly SpecificationNumberingService $numberingService,
    ) {
    }

    public function getOrCreateDraft(Model $user): Specification
    {
        return Specification::firstOrCreate(
            [
                'company_id' => $user->company->id,
                'status' => QuotationStatus::DRAFT,
            ],
            [
                'reference' => $this->numberingService->generateReference(),
                'general_notes' => [],
                'show_quantity' => true,
                'show_approval' => true,
                'show_reference' => true,
            ]
        );
    }

    public function update(Specification $specification, array $attributes): Specification
    {
        return DB::transaction(function () use ($specification, $attributes): Specification {
            $allowed = Arr::only($attributes, [
                'reference',
                'title',
                'general_notes',
                'show_quantity',
                'show_approval',
                'show_reference',
            ]);

            if ($allowed === []) {
                return $this->loadRelations($specification);
            }

            $specification->fill($allowed);
            $specification->save();

            return $this->loadRelations($specification);
        });
    }

    public function addItem(Specification $specification, Product $product, array $attributes, array $accessories = []): SpecificationItem
    {
        return DB::transaction(function () use ($specification, $product, $attributes, $accessories): SpecificationItem {
            $itemInfo = $this->numberingService->getNextItemInfo($specification);
            $itemInfo['item_ref'] = $attributes['item_ref'] ?? $itemInfo['item_ref'];
            $payload = $this->prepareItemPayload(
                $specification,
                $product,
                array_merge($attributes, $itemInfo)
            );

            $item = $specification->items()->create($payload);

            $providedPayloads = $this->accessoryService
                ->buildProvidedAccessoriesPayloads($item, $accessories);

            if (! empty($providedPayloads)) {
                $item->accessories()->createMany($providedPayloads);
            }

            return $item->load('accessories');
        });
    }

    public function replaceItem(SpecificationItem $current, Product $replacement, array $attributes, array $accessories = []): SpecificationItem
    {
        return DB::transaction(function () use ($current, $replacement, $attributes, $accessories): SpecificationItem {
            $specification = $current->specification;

            if (! array_key_exists('item_ref', $attributes) && $current->item_ref !== null) {
                $attributes['item_ref'] = $current->item_ref;
            }

            if (! array_key_exists('position', $attributes) && $current->position !== null) {
                $attributes['position'] = $current->position;
            }

            $current->delete();

            $payload = $this->prepareItemPayload($specification, $replacement, $attributes);

            /** @var SpecificationItem $item */
            $item = $specification->items()->create($payload);

            $providedPayloads = $this->accessoryService
                ->buildProvidedAccessoriesPayloads($item, $accessories);
            if ($providedPayloads !== []) {
                $item->accessories()->createMany($providedPayloads);
            }

            return $item->load('accessories');
        });
    }

    public function updateItem(SpecificationItem $item, array $attributes): SpecificationItem
    {
        return DB::transaction(function () use ($item, $attributes): SpecificationItem {
            $allowed = Arr::only($attributes, [
                'item_ref',
                'position',
                'notes',
                'quantity',
            ]);

            if ($allowed !== []) {
                $item->fill($allowed);
                $item->save();
            }

            return $item->fresh('accessories');
        });
    }

    public function deleteItem(SpecificationItem $item): void
    {
        $item->delete();
    }

    public function addAccessory(SpecificationItem $item, Product $accessory, array $attributes): SpecificationItemAccessory
    {
        return DB::transaction(function () use ($item, $accessory, $attributes): SpecificationItemAccessory {
            $nextPosition = $this->numberingService->nextAccessoryPosition($item);
            $nextRef = $this->numberingService->generateAccessoryReference($item);

            $attributes['position'] = $attributes['position'] ?? $nextPosition;
            $attributes['item_ref'] = $attributes['item_ref'] ?? $nextRef;

            $payload = $this->accessoryService->buildAccessoryPayload($item, $accessory, $attributes);

            /** @var SpecificationItemAccessory $record */
            $record = $item->accessories()->create($payload);

            return $record;
        });
    }

    public function updateAccessory(SpecificationItemAccessory $accessory, array $attributes): SpecificationItemAccessory
    {
        return DB::transaction(function () use ($accessory, $attributes): SpecificationItemAccessory {
            $allowed = Arr::only($attributes, [
                'item_ref',
                'position',
                'notes',
                'quantity',
                'accessory_type',
            ]);

            if ($allowed !== []) {
                $accessory->fill($allowed);
                $accessory->save();
            }

            return $accessory->fresh();
        });
    }

    public function deleteAccessory(SpecificationItemAccessory $accessory): void
    {
        $accessory->delete();
    }

    public function refreshTotals(Specification $specification): Specification
    {
        // Specifications have no monetary totals; just reload relations.
        return $this->loadRelations($specification->fresh());
    }

    public function loadRelations(Specification $specification): Specification
    {
        return $specification->loadMissing([
            'company',
            'items.accessories',
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function prepareItemPayload(Specification $specification, Product $product, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $itemRef = $attributes['item_ref'] ?? $this->numberingService->generateItemReference($specification);
        $position = $attributes['position'] ?? $this->numberingService->nextItemPosition($specification);

        return [
            'item_ref' => $itemRef,
            'position' => $position,
            'specification_id' => $specification->getKey(),
            'solution_id' => $product->solution_id,
            'department_id' => $product->department_id,
            'subcategory_id' => $product->subcategory_id,
            'family_id' => $product->family_id,
            'supplier_id' => $product->supplier_id,
            'brand_id' => $product->brand?->id,
            'brand_name' => $product->brand?->name ?? '',
            'product_id' => $product->getKey(),
            'product_code' => $product->code,
            'product_name' => $product->name,
            'product_description' => $product->description,
            'product_origin' => $product->origin,
            'notes' => $attributes['notes'] ?? null,
            'quantity' => $quantity,
        ];
    }
}



<?php

namespace App\Modules\Products\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Shared\Support\Traits\HandleMedia;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\Products\Domain\Models\{Product, ProductAccessory};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    use HandlesTranslations, HandleMedia;

    public function create(array $attributes, array $translations, array $media): Product
    {
        return $this->fillProduct(
            new Product(),
            $attributes,
            $translations,
            $media,
        );
    }

    public function update(Product $product, array $attributes, array $translations, array $media): Product
    {
        return $this->fillProduct(
            $product,
            $attributes,
            $translations,
            $media,
            true
        );
    }

    public function delete(Product $product): void
    {
        DB::transaction(static function () use ($product): void {
            $product->delete();
        });
    }

    public function find(int $id): ?Product
    {
        return Product::query()
            ->with($this->allRelations())
            ->find($id);
    }

    public function getByFamily(int $familyId, ?int $supplierId = null): Collection
    {
        return Product::query()
            ->with([
                'media',
                'fieldValues.field',
                'family.subcategory.department.solution',
                'family.department.supplierBrand.brand',
                'family.department.department',
                'family.subcategory',
            ])
            ->where('family_id', $familyId)
            ->when($supplierId !== null, static function ($query) use ($supplierId): void {
                $query->whereHas('family', static function ($familyQuery) use ($supplierId): void {
                    $familyQuery->where('supplier_id', $supplierId);
                });
            })
            ->orderBy('code')
            ->get();
    }

    public function cutPasteProduct(Product $product, int $family_id): Product
    {
        return DB::transaction(function () use ($product, $family_id): Product {
            $product->family_id = $family_id;
            $product->save();

            return $this->loadAggregates($product);
        });
    }

    public function compare(int $firstProduct, int $secondProduct): Collection
    {
        $products = Product::query()
            ->whereIn('id', [$firstProduct, $secondProduct])
            ->with($this->allRelations())
            ->limit(2)
            ->get();

        if ($products->count() < 2) {
            throw new \InvalidArgumentException('Both products must exist for comparison.', Response::HTTP_NOT_FOUND);
        }

        if ($products->first()->family->subcategory_id !== $products->last()->family->subcategory_id) {
            throw new \InvalidArgumentException('Products must belong to the same roots for comparison.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $products;
    }

    public function attachAccessory( Product $product, Product $accessory, AccessoryType $type, ?int $quantity = null): ProductAccessory {
        return DB::transaction(function () use ($product, $accessory, $type, $quantity): ProductAccessory {
            /** @var ProductAccessory $record */
            $record = $product->accessories()->updateOrCreate(
                [
                    'accessory_id' => $accessory->getKey(),
                ],
                [
                    'accessory_type' => $type,
                    'quantity' => $quantity !== null ? (string) $quantity : null,
                ]
            );

            return $record->load('accessory.translations');
        });
    }

    private function loadAggregates(Product $product): Product
    {
        return $product->load($this->allRelations());
    }

    protected function fillProduct(Product $product, array $attributes, array $translations, array $media, bool $isUpdate = false): Product
    {
        $product->fill($attributes);
        $this->fillTranslations($product, $translations);
        $product->save();
        $this->syncMedia($product, $media, $isUpdate);
        return $product;
    }

    private function allRelations()
    {
        return [
            'media',
            'translations',
            'fieldValues.field.translations',
            'prices',
            'family.translations',
            'family.supplier',
            'family.subcategory.department.solution.translations',
            'family.department.supplierBrand.brand',
            'family.department.department.translations',
            'family.subcategory.translations',
            'accessories.accessory.translations',
            'accessories.accessory.media',
            'accessories.accessory.fieldValues.field.translations',
            'accessories.accessory.family.translations',
            'accessories.accessory.family.supplier',
            'accessories.accessory.family.subcategory.department.solution.translations',
            'accessories.accessory.family.department.supplierBrand.brand',
            'accessories.accessory.family.department.department.translations',
            'accessories.accessory.family.subcategory.translations',
        ];
    }
}

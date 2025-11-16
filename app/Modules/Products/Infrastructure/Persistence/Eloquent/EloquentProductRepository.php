<?php

namespace App\Modules\Products\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Products\Domain\Models\{Product, ProductAccessory};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class EloquentProductRepository implements ProductRepositoryInterface
{
    use HandlesTranslations;

    public function create(array $attributes, array $translations, array $values, array $relations = []): Product
    {
        return DB::transaction(function () use ($attributes, $translations, $values, $relations): Product {
            $product = new Product();
            $product->fill($attributes);
            $this->fillTranslations($product, $translations);
            $product->save();

            $this->syncFieldValues($product, $values, true);
            $this->syncPrices($product, $relations, true);
            $this->syncAccessories($product, $relations, true);
            // $this->syncColors($product, $relations, true);
            $this->syncMedia($product, $relations);

            return $this->loadAggregates($product);
        });
    }

    public function update(Product $product, array $attributes, array $translations, array $values, array $relations = []): Product
    {
        return DB::transaction(function () use ($product, $attributes, $translations, $values, $relations): Product {
            $currentTemplateId = (int) $product->data_template_id;
            $templateChanged = array_key_exists('data_template_id', $attributes)
                && (int) $attributes['data_template_id'] !== $currentTemplateId;

            if ($attributes !== []) {
                $product->fill($attributes);
            }

            if ($translations !== []) {
                $this->fillTranslations($product, $translations);
            }

            $product->save();

            if ($templateChanged || $values !== []) {
                $this->syncFieldValues($product, $values, $templateChanged);
            }

            $this->syncPrices($product, $relations, (bool) Arr::get($relations, 'sync_prices', false));
            $this->syncAccessories($product, $relations, (bool) Arr::get($relations, 'sync_accessories', false));
            // $this->syncColors($product, $relations, (bool) Arr::get($relations, 'sync_colors', false));
            $this->syncMedia($product, $relations);

            return $this->loadAggregates($product);
        });
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
            ->with([
                'fieldValues.field.translations',
                'prices',
                'accessories.accessory.translations',
                'family.subcategory.department',
                'family.supplier',
            ])
        ->find($id);
    }

    public function getByFamily(int $familyId, ?int $supplierId = null): Collection
    {
        return Product::query()
            ->with([
                'fieldValues.field.translations',
                'prices',
                'accessories.accessory.translations',
                'family.subcategory.department',
                'family.supplier',
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

    public function attachAccessory(
        Product $product,
        Product $accessory,
        AccessoryType $type,
        ?int $quantity = null
    ): ProductAccessory {
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
        return $product->load([
            'fieldValues.field.translations',
            'prices',
            'accessories.accessory.translations',
            'family.subcategory.department',
            'family.supplier',
        ]);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function syncFieldValues(Product $product, array $values, bool $overwriteMissing): void
    {
        $template = DataTemplate::query()
            ->with('fields')
            ->find($product->data_template_id);

        if ($template === null) {
            return;
        }

        $fields = $template->fields->keyBy('name');
        $retainedFieldIds = [];

        foreach ($values as $key => $value) {
            if (! $fields->has($key)) {
                continue;
            }

            /** @var DataField $field */
            $field = $fields->get($key);
            $normalizedValue = $this->prepareValue($field, $value);

            $productFieldValue = $product->fieldValues()
                ->updateOrCreate(
                    [
                        'data_field_id' => $field->getKey(),
                    ],
                    [
                        'value' => $normalizedValue,
                    ],
                );

            $retainedFieldIds[] = (int) $productFieldValue->data_field_id;
        }

        if ($overwriteMissing) {
            $query = $product->fieldValues();

            if ($retainedFieldIds !== []) {
                $query->whereNotIn('data_field_id', $retainedFieldIds);
            }

            $query->delete();
        }
    }

    private function prepareValue(DataField $field, mixed $value): mixed
    {
        $type = $field->type;

        return match ($type) {
            DataFieldType::MULTISELECT => array_values(Arr::wrap($value)),
            DataFieldType::BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            DataFieldType::NUMBER => is_numeric($value) ? $value + 0 : $value,
            default => $value,
        };
    }

    /**
     * @param  array<string, mixed>  $relations
     */
    private function syncPrices(Product $product, array $relations, bool $overwriteMissing): void
    {
        $prices = Arr::get($relations, 'prices', []);

        if (! is_array($prices)) {
            return;
        }

        if ($overwriteMissing) {
            $product->prices()->delete();
        }

        foreach ($prices as $price) {
            $product->prices()->create([
                'price' => Arr::get($price, 'price'),
                'from' => Arr::get($price, 'from'),
                'to' => Arr::get($price, 'to'),
                'currency' => Arr::get($price, 'currency'),
                'delivery_time_unit' => Arr::get($price, 'delivery_time_unit'),
                'delivery_time_value' => Arr::get($price, 'delivery_time_value'),
                'vat_status' => Arr::get($price, 'vat_status'),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $relations
     */
    private function syncAccessories(Product $product, array $relations, bool $overwriteMissing): void
    {
        $accessories = Arr::get($relations, 'accessories', []);

        if (! is_array($accessories)) {
            return;
        }

        $retained = [];

        foreach ($accessories as $index => $accessory) {
            $code = Arr::get($accessory, 'code');
            $type = Arr::get($accessory, 'type');
            $quantity = Arr::get($accessory, 'quantity');

            if (! is_string($code) || ! is_string($type)) {
                continue;
            }

            $accessoryProduct = Product::query()->where('code', $code)->first();

            if ($accessoryProduct === null) {
                throw ValidationException::withMessages([
                    'accessories.' . $index . '.code' => trans('validation.exists', ['attribute' => 'accessory code']),
                ]);
            }

            if ($accessoryProduct->getKey() === $product->getKey()) {
                continue;
            }

            $productAccessory = $product->accessories()->updateOrCreate(
                [
                    'accessory_id' => $accessoryProduct->getKey(),
                ],
                [
                    'accessory_type' => AccessoryType::from($type),
                    'quantity' => $quantity,
                ],
            );

            $retained[] = (int) $productAccessory->accessory_id;
        }

        if ($overwriteMissing) {
            $query = $product->accessories();

            if ($retained !== []) {
                $query->whereNotIn('accessory_id', $retained);
            }

            $query->delete();
        }
    }

    /**
     * @param  array<string, mixed>  $relations
     */
    private function syncColors(Product $product, array $relations, bool $shouldSync): void
    {
        if (! $shouldSync) {
            return;
        }

        $colorIds = Arr::get($relations, 'color_ids', []);

        if (! is_array($colorIds)) {
            return;
        }

        $product->colors()->sync(array_filter($colorIds));
    }

    /**
     * @param  array<string, mixed>  $relations
     */
    private function syncMedia(Product $product, array $relations): void
    {
        $media = Arr::get($relations, 'media', []);

        if (! is_array($media)) {
            return;
        }

        foreach (['gallery', 'documents', 'dimensions'] as $collection) {
            $files = Arr::get($media, $collection, []);
            if (! is_array($files) || $files === []) {
                continue;
            }

            $product->clearMediaCollection($collection);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $product->addMedia($file)->toMediaCollection($collection);
                }
            }
        }
    }

    public function findAccessoryOfProduct(int $product_id, int $accessory_id): ?ProductAccessory
    {
        return ProductAccessory::query()
            ->with([
                'product',
                'accessory',
            ])
        ->where('product_id', $product_id)
        ->where('accessory_id', $accessory_id)
        ->first();
    }
}

<?php

namespace App\Modules\Products\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;
use App\Modules\Products\Domain\Models\{Product, ProductAccessory};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

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
            // $this->syncMedia($product, $relations);
            $this->syncProductMedia($product, $relations['media'] ?? [], false);
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
            // $this->syncOldGallery($product, $relations['media']['old_gallery'] ?? []);
            // $this->syncMedia($product, $relations);
            $this->syncProductMedia($product, $relations['media'] ?? [], true);
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

    public function cutPasteProduct(Product $product, int $family_id): Product
    {
        return DB::transaction(function () use ($product, $family_id): Product {
            $product->family_id = $family_id;
            $product->save();

            return $this->loadAggregates($product);
        });
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

    private function syncProductMedia(Product $product, array $media, bool $isUpdate = false): void
    {
        $uploadedFiles = $this->extractUploadedFiles($media);
        $localUrls     = $this->extractLocalUrls($media);

        if ($isUpdate) {
            $this->syncRemovedMedia($product, $localUrls);
        }

        $this->attachLocalMedia($product, $localUrls);
        $this->attachUploadedFiles($product, $uploadedFiles);
    }

    private function extractUploadedFiles(array $media): array
    {
        return collect($media)
            ->flatten()
            ->filter(fn ($item) => $item instanceof UploadedFile)
            ->all();
    }

    private function extractLocalUrls(array $media): array
    {
        return collect($media)
            ->flatten()
            ->filter(fn ($item) => is_string($item))
            ->all();
    }

    private function syncRemovedMedia(Product $product, array $urls): void
    {
        $keepFileNames = collect($urls)
            ->map(fn ($url) => basename($url))
            ->unique();

        $toKeep = $product->media()
            ->where('collection_name', 'gallery')
            ->whereIn('file_name', $keepFileNames)
            ->get();

        $product->clearMediaCollectionExcept('gallery', excludedMedia: $toKeep);
    }

    private function attachLocalMedia(Product $product, array $urls): void
    {
        collect($urls)
            ->map(fn ($url) => $this->resolveLocalStoragePath($url))
            ->filter()
            ->each(function ($path) use ($product) {
                $exists = $product->media()
                    ->where('collection_name', 'gallery')
                    ->where('file_name', basename($path))
                    ->exists();

                if ($exists) {
                    return;
                }

                $product->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('gallery');
            });
    }

    private function attachUploadedFiles(Product $product, array $files): void
    {
        foreach ($files as $file) {
            $product->addMedia($file)
                ->toMediaCollection('gallery');
        }
    }

    private function resolveLocalStoragePath(string $url): ?string
    {
        $appUrl = rtrim(config('app.url'), '/');

        if (!Str::startsWith($url, $appUrl . '/storage/')) {
            return null;
        }

        $relativePath = Str::after(
            parse_url($url, PHP_URL_PATH),
            '/storage/'
        );

        return Storage::disk('public')->exists($relativePath)
            ? Storage::disk('public')->path($relativePath)
            : null;
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

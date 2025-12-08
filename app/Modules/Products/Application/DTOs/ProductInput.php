<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;

class ProductInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     * @param  array<int, array<string, mixed>>  $prices
     * @param  array<int, array<string, mixed>>  $accessories
     * @param  array<int, int>  $colorIds
     * @param  array<string, array<int, UploadedFile>>  $media
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $values,
        public readonly array $prices,
        public readonly bool $shouldSyncPrices,
        public readonly array $accessories,
        public readonly bool $shouldSyncAccessories,
        // public readonly bool $shouldSyncColors,
        public readonly array $media,
        public readonly ?int $supplierId,
        public readonly ?array $oldGallery = [],
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $values = Arr::pull($payload, 'values', []);

        $shouldSyncPrices = Arr::exists($payload, 'prices');
        $prices = Arr::pull($payload, 'prices', []);

        $shouldSyncAccessories = Arr::exists($payload, 'accessories');
        $accessories = Arr::pull($payload, 'accessories', []);
        $oldGallery = Arr::pull($payload, 'old_gallery', []);

        $media = [
            'gallery' => self::extractFiles(Arr::pull($payload, 'gallery', [])),
            'documents' => self::extractFiles(Arr::pull($payload, 'documents', [])),
            'dimensions' => self::extractFiles(Arr::pull($payload, 'dimensions', [])),
        ];


        $supplierId = Arr::pull($payload, 'supplier_id');

        return new self(
            attributes: $payload,
            translations: is_array($translations) ? $translations : [],
            values: is_array($values) ? $values : [],
            prices: array_values(is_array($prices) ? $prices : []),
            shouldSyncPrices: $shouldSyncPrices,
            accessories: array_values(is_array($accessories) ? $accessories : []),
            shouldSyncAccessories: $shouldSyncAccessories,
            media: $media,
            oldGallery: is_array($oldGallery) ? $oldGallery : [],
            supplierId: is_numeric($supplierId) ? (int) $supplierId : null,
        );
    }

    /**
     * @param  mixed  $files
     * @return array<int, UploadedFile>
     */
    private static function extractFiles(mixed $files): array
    {
        $files = is_array($files) ? $files : [$files];

        return array_values(array_filter(
            $files,
            static fn ($file) => $file instanceof UploadedFile
        ));
    }
}

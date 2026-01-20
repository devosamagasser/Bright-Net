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

        $media = [
            'gallery' => [
                'files' => self::extractFiles(Arr::pull($payload, 'gallery', [])),
                'urls' =>  Arr::pull($payload, 'old_gallery', []),
            ],
            'documents' => [
                'files' => self::extractFiles(Arr::pull($payload, 'documents', [])),
                'urls' =>  Arr::pull($payload, 'old_documents', []),
            ],
            'dimensions' => [
                'files' => self::extractFiles(Arr::pull($payload, 'dimensions', [])),
                'urls' =>  Arr::pull($payload, 'old_dimensions', []),
            ],
            'quotation_image' => [
                'files' => self::extractFiles(Arr::pull($payload, 'quotation_image')),
                'urls' =>  [Arr::pull($payload, 'old_quotation_image', '')],
            ],
        ];

        $supplierId = Arr::pull($payload, 'supplier_id');

        return new self(
            attributes: $payload,
            translations: is_array($translations) ? $translations : [],
            values: is_array($values) ? $values : [],
            prices: array_values(
                is_array($prices)
                    ? collect($prices)
                        ->filter(fn ($price) => (
                            $price['price'] !== null &&
                            $price['from'] !== null &&
                            $price['to'] !== null &&
                            $price['currency'] !== null &&
                            $price['vat_status'] !== null &&
                            $price['delivery_time_unit'] !== null &&
                            $price['delivery_time_value'] !== null
                            ))
                        ->values()
                        ->all()
                    : []
            ),
            shouldSyncPrices: $shouldSyncPrices,
            accessories: array_values(is_array($accessories) ? $accessories : []),
            shouldSyncAccessories: $shouldSyncAccessories,
            media: $media,
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

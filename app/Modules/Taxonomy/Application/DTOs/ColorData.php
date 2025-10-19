<?php

namespace App\Modules\Taxonomy\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Taxonomy\Domain\Models\Color;

class ColorData
{
    /**
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $hexCode,
        public readonly array $translations,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Color $color): self
    {
        return new self(
            id: $color->getKey(),
            name: (string) $color->name,
            hexCode: (string) $color->hex_code,
            translations: $color->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => ['name' => $translation->name],
                ])->toArray(),
            createdAt: $color->created_at?->toISOString() ?? '',
            updatedAt: $color->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Color>  $colors
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function collection(Collection $colors): Collection
    {
        return $colors->map(static fn (Color $color) => self::fromModel($color));
    }
}

<?php

namespace App\Modules\DataSheets\Application\DTOs;

use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class DataTemplateData
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array<string, mixed>>  $translations
     * @param  array<int, DataFieldData>  $fields
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $fields,
    ) {
    }

    public static function fromModel(DataTemplate $template): self
    {
        return new self(
            attributes: [
                'id' => $template->getKey(),
                'subcategory_id' => $template->subcategory_id,
                'type' => $template->type instanceof DataTemplateType ? $template->type->value : $template->type,
            ],
            translations: $template->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            })->all(),
            fields: $template->fields->map(fn ($field) => DataFieldData::fromModel($field))->all(),
        );
    }
}

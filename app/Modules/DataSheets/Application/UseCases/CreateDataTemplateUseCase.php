<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\DataSheets\Application\DTOs\{DataTemplateData, DataTemplateInput};
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class CreateDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(DataTemplateInput $input, ?DataTemplateType $type = null): DataTemplateData
    {
        $attributes = $input->attributes;

        if ($type) {
            $attributes['type'] = $type->value;
        }

        $typeValue = $attributes['type'] ?? null;
        $typeEnum = is_string($typeValue) ? DataTemplateType::tryFrom($typeValue) : null;

        if ($typeEnum) {
            $existingTemplate = $this->repository->findBySubcategoryAndType(
                $attributes['subcategory_id'],
                $typeEnum,
            );

            if ($existingTemplate) {
                throw ValidationException::withMessages([
                    'type' => trans('validation.unique', ['attribute' => 'type']),
                ]);
            }
        }

        $template = $this->repository->create(
            attributes: $attributes,
            translations: $input->translations,
            fields: $input->fields,
        );

        return DataTemplateData::fromModel($template->load('fields'));
    }
}

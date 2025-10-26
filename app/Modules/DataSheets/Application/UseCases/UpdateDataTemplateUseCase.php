<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\DataSheets\Application\DTOs\{DataTemplateData, DataTemplateInput};
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class UpdateDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId, DataTemplateInput $input, ?DataTemplateType $type = null): DataTemplateData
    {
        $template = $this->repository->find($templateId, $type);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        $attributes = $input->attributes;

        if ($type) {
            $attributes['type'] = $type->value;
        }

        $subcategoryId = $attributes['subcategory_id'] ?? $template->subcategory_id;
        $currentType = $template->type;
        $typeValue = $attributes['type'] ?? ($currentType instanceof DataTemplateType ? $currentType->value : $currentType);
        $typeEnum = is_string($typeValue) ? DataTemplateType::tryFrom($typeValue) : null;

        if ($typeEnum) {
            $existingTemplate = $this->repository->findBySubcategoryAndType($subcategoryId, $typeEnum);

            if ($existingTemplate && $existingTemplate->getKey() !== $template->getKey()) {
                throw ValidationException::withMessages([
                    'type' => trans('validation.unique', ['attribute' => 'type']),
                ]);
            }
        }

        $template = $this->repository->update(
            $template,
            attributes: $attributes,
            translations: $input->translations,
            fields: $input->fields,
        );

        return DataTemplateData::fromModel($template->load('fields'));
    }
}

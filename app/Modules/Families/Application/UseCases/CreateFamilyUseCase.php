<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Families\Application\DTOs\{FamilyData, FamilyInput};
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Families\Domain\Services\FamilyDataValidator;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class CreateFamilyUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $repository,
        private readonly DataTemplateRepositoryInterface $dataTemplates,
        private readonly FamilyDataValidator $validator,
    ) {
    }

    public function handle(FamilyInput $input): FamilyData
    {
        $attributes = $input->attributes;

        $templateId = (int) ($attributes['data_template_id'] ?? 0);
        $template = $this->dataTemplates->find($templateId, DataTemplateType::FAMILY);

        if (! $template) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.exists', ['attribute' => 'data_template_id']),
            ]);
        }

        $subcategoryId = (int) ($attributes['subcategory_id'] ?? 0);
        if ($template->subcategory_id !== $subcategoryId) {
            throw ValidationException::withMessages([
                'subcategory_id' => trans('validation.in', ['attribute' => 'subcategory_id']),
            ]);
        }

        $normalizedValues = $this->validator->validate($template, $input->values);

        $valuePayload = [];
        foreach ($template->fields as $field) {
            if (! array_key_exists($field->slug, $normalizedValues)) {
                continue;
            }

            $valuePayload[] = [
                'data_field_id' => $field->getKey(),
                'value' => $normalizedValues[$field->slug],
            ];
        }

        $family = $this->repository->create(
            $attributes,
            $input->translations,
            $valuePayload,
        );

        return FamilyData::fromModel($family);
    }
}

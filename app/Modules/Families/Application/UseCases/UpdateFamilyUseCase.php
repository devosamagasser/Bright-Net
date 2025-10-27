<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Families\Application\DTOs\{FamilyData, FamilyInput};
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Families\Domain\Services\FamilyDataValidator;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class UpdateFamilyUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $repository,
        private readonly DataTemplateRepositoryInterface $dataTemplates,
        private readonly FamilyDataValidator $validator,
    ) {
    }

    public function handle(int $familyId, FamilyInput $input): FamilyData
    {
        $family = $this->repository->find($familyId);

        if (! $family) {
            throw new ModelNotFoundException();
        }

        $attributes = $input->attributes;

        $subcategoryId = array_key_exists('subcategory_id', $attributes)
            ? (int) $attributes['subcategory_id']
            : (int) $family->subcategory_id;

        $templateId = array_key_exists('data_template_id', $attributes)
            ? (int) $attributes['data_template_id']
            : (int) $family->data_template_id;

        $template = $this->dataTemplates->find($templateId, DataTemplateType::FAMILY);

        if (! $template) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.exists', ['attribute' => 'data_template_id']),
            ]);
        }

        if ($template->subcategory_id !== $subcategoryId) {
            throw ValidationException::withMessages([
                'subcategory_id' => trans('validation.in', ['attribute' => 'subcategory_id']),
            ]);
        }

        $normalizedValues = null;
        $shouldUpdateValues = $input->hasValues;

        if ($templateId !== (int) $family->data_template_id) {
            if (! $input->hasValues) {
                throw ValidationException::withMessages([
                    'values' => trans('validation.required', ['attribute' => 'values']),
                ]);
            }

            $shouldUpdateValues = true;
        }

        if ($shouldUpdateValues) {
            $normalizedValues = $this->validator->validate($template, $input->values);
        }

        $valuePayload = null;
        if ($normalizedValues !== null) {
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
        }

        $mergedAttributes = array_merge(
            [
                'subcategory_id' => $family->subcategory_id,
                'supplier_id' => $family->supplier_id,
                'data_template_id' => $family->data_template_id,
            ],
            $attributes,
            ['subcategory_id' => $subcategoryId, 'data_template_id' => $templateId]
        );

        $family = $this->repository->update(
            $family,
            $mergedAttributes,
            $input->translations,
            $valuePayload,
        );

        return FamilyData::fromModel($family);
    }
}

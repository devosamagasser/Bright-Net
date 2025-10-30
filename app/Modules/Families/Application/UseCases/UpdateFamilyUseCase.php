<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Families\Application\DTOs\{FamilyData, FamilyInput};
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Domain\Models\DataTemplate;

class UpdateFamilyUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $families,
        private readonly DataTemplateRepositoryInterface $templates,
    ) {
    }

    public function handle(Family $family, FamilyInput $input): FamilyData
    {
        $attributes = $input->attributes;

        $targetSubcategoryId = (int) ($attributes['subcategory_id'] ?? $family->subcategory_id);
        $targetTemplateId = (int) ($attributes['data_template_id'] ?? $family->data_template_id);

        $template = $this->requireTemplate($targetTemplateId);
        $this->assertTemplateMatchesSubcategory($template, $targetSubcategoryId);

        $updatedFamily = $this->families->update(
            $family,
            $attributes,
            $input->translations, $input->values,
            $input->image
        );

        return FamilyData::fromModel($updatedFamily);
    }

    private function requireTemplate(int $templateId): DataTemplate
    {
        $template = $this->templates->find($templateId, DataTemplateType::FAMILY);

        if ($template === null) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.exists', ['attribute' => 'data template']),
            ]);
        }

        return $template;
    }

    private function assertTemplateMatchesSubcategory(DataTemplate $template, int $subcategoryId): void
    {
        if ((int) $template->subcategory_id !== $subcategoryId) {
            throw ValidationException::withMessages([
                'data_template_id' => trans('validation.in', ['attribute' => 'data template']),
            ]);
        }
    }
}

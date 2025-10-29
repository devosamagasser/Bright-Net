<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Families\Application\DTOs\{FamilyData, FamilyInput};
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Domain\Models\DataTemplate;

class CreateFamilyUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $families,
        private readonly DataTemplateRepositoryInterface $templates,
    ) {
    }

    public function handle(FamilyInput $input): FamilyData
    {
        $attributes = $input->attributes;

        $template = $this->requireTemplate((int) ($attributes['data_template_id'] ?? 0));
        $subcategoryId = (int) ($attributes['subcategory_id'] ?? 0);

        $this->assertTemplateMatchesSubcategory($template, $subcategoryId);

        $family = $this->families->create($attributes, $input->translations, $input->values);

        return FamilyData::fromModel($family);
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

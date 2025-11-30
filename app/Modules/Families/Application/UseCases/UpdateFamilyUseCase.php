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
        $attributes = $input->attributes + [
            'data_template_id' => $this->templates->findBySubcategoryAndType(
                $input->attributes['subcategory_id'],
                DataTemplateType::FAMILY
            )?->id ?? null,
        ];
        $updatedFamily = $this->families->update(
            $family,
            $attributes,
            $input->translations, $input->values,
            $input->image
        );

        return FamilyData::fromModel($updatedFamily);
    }

}

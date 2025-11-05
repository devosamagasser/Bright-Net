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
        $attributes = $input->attributes + [
            'data_template_id' => $this->templates->findBySubcategoryAndType(
                $input->attributes['subcategory_id'],
                DataTemplateType::FAMILY
            )->first()->id
        ];

        $family = $this->families->create(
            $attributes,
            $input->translations,
            $input->values ?? [],
            $input->image
        );

        return FamilyData::fromModel($family);
    }
}

<?php

namespace App\Modules\DataSheets\Application\UseCases;

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

        $template = $this->repository->create(
            attributes: $attributes,
            translations: $input->translations,
            fields: $input->fields,
        );

        return DataTemplateData::fromModel($template->load('fields'));
    }
}

<?php

namespace App\Modules\DataSheets\Application\UseCases;

use App\Modules\DataSheets\Application\DTOs\{DataTemplateData, DataTemplateInput};
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class CreateDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(DataTemplateInput $input): DataTemplateData
    {
        $template = $this->repository->create(
            attributes: $input->attributes,
            translations: $input->translations,
            fields: $input->fields,
        );

        return DataTemplateData::fromModel($template->load('fields'));
    }
}

<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\DataSheets\Application\DTOs\{DataTemplateData, DataTemplateInput};
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class UpdateDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId, DataTemplateInput $input): DataTemplateData
    {
        $template = $this->repository->find($templateId);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        $template = $this->repository->update(
            $template,
            attributes: $input->attributes,
            translations: $input->translations,
            fields: $input->fields,
        );

        return DataTemplateData::fromModel($template->load('fields'));
    }
}

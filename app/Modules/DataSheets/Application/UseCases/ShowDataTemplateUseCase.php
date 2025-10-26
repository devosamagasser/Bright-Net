<?php

namespace App\Modules\DataSheets\Application\UseCases;

use App\Modules\DataSheets\Application\DTOs\DataTemplateData;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId): DataTemplateData
    {
        $template = $this->repository->find($templateId);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        return DataTemplateData::fromModel($template);
    }
}

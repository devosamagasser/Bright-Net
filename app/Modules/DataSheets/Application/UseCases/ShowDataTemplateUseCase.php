<?php

namespace App\Modules\DataSheets\Application\UseCases;

use App\Modules\DataSheets\Application\DTOs\DataTemplateData;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId, ?DataTemplateType $type = null): DataTemplateData
    {
        $template = $this->repository->find($templateId, $type);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        return DataTemplateData::fromModel($template);
    }
}

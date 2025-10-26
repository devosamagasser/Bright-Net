<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class DeleteDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId, ?DataTemplateType $type = null): void
    {
        $template = $this->repository->find($templateId, $type);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($template);
    }
}

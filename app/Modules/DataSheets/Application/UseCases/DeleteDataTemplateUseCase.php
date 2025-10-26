<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class DeleteDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $templateId): void
    {
        $template = $this->repository->find($templateId);

        if (! $template) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($template);
    }
}

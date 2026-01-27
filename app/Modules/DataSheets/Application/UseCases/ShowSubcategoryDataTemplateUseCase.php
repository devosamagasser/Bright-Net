<?php

namespace App\Modules\DataSheets\Application\UseCases;

use App\Modules\DataSheets\Application\DTOs\DataTemplateData;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowSubcategoryDataTemplateUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $subcategoryId, DataTemplateType $type): DataTemplateData
    {
        $template = $this->repository->findBySubcategoryAndType($subcategoryId, $type);
        if($template === null) {
            throw new ModelNotFoundException();
        }
        return DataTemplateData::fromModel($template);
    }
}

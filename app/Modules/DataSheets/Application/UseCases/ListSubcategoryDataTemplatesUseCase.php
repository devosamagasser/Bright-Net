<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Support\Collection;
use App\Modules\DataSheets\Application\DTOs\DataTemplateData;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class ListSubcategoryDataTemplatesUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    /**
     * @return \Illuminate\Support\Collection<int, DataTemplateData>
     */
    public function handle(int $subcategoryId): Collection
    {
        $templates = $this->repository->getBySubcategory($subcategoryId);

        return DataTemplateData::collection($templates);
    }
}

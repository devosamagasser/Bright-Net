<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Application\DTOs\CollectionInput;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;

class CreateCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    ) {
    }

    public function handle(CollectionInput $input, int $companyId): CollectionData
    {
        $collection = $this->repository->create(
            $input->attributes + ['company_id' => $companyId]
        );

        return CollectionData::fromModel($collection);
    }
}


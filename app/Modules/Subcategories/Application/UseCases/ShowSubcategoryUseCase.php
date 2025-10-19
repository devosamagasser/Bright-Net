<?php

namespace App\Modules\Subcategories\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Subcategories\Application\DTOs\SubcategoryData;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class ShowSubcategoryUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(int $subcategoryId): SubcategoryData
    {
        $subcategory = $this->repository->find($subcategoryId);

        if (! $subcategory) {
            throw new ModelNotFoundException();
        }

        return SubcategoryData::fromModel($subcategory);
    }
}

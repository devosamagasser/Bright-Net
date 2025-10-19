<?php

namespace App\Modules\Subcategories\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Subcategories\Application\DTOs\{SubcategoryData, SubcategoryInput};
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class UpdateSubcategoryUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(int $subcategoryId, SubcategoryInput $input): SubcategoryData
    {
        $subcategory = $this->repository->find($subcategoryId);

        if (! $subcategory) {
            throw new ModelNotFoundException();
        }

        $subcategory = $this->repository->update(
            $subcategory,
            $input->attributes,
            $input->translations,
        );

        return SubcategoryData::fromModel($subcategory->load('department'));
    }
}

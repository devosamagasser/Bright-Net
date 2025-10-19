<?php

namespace App\Modules\Subcategories\Application\UseCases;

use App\Modules\Subcategories\Application\DTOs\{SubcategoryData, SubcategoryInput};
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class CreateSubcategoryUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(SubcategoryInput $input): SubcategoryData
    {
        $subcategory = $this->repository->create(
            $input->attributes,
            $input->translations,
        );

        return SubcategoryData::fromModel($subcategory->load('department'));
    }
}

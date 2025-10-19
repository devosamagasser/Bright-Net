<?php

namespace App\Modules\Subcategories\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class DeleteSubcategoryUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(int $subcategoryId): void
    {
        $subcategory = $this->repository->find($subcategoryId);

        if (! $subcategory) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($subcategory);
    }
}

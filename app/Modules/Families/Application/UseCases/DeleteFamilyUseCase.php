<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class DeleteFamilyUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $familyId): void
    {
        $family = $this->repository->find($familyId);

        if (! $family) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($family);
    }
}

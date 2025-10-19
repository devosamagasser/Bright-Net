<?php

namespace App\Modules\Taxonomy\Application\UseCases;

use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteColorUseCase
{
    public function __construct(
        private readonly ColorRepositoryInterface $repository,
    ) {
    }

    public function handle(int $colorId): void
    {
        $color = $this->repository->find($colorId);

        if (! $color) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($color);
    }
}

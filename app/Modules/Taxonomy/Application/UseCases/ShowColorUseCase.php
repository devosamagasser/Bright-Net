<?php

namespace App\Modules\Taxonomy\Application\UseCases;

use App\Modules\Taxonomy\Application\DTOs\ColorData;
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowColorUseCase
{
    public function __construct(
        private readonly ColorRepositoryInterface $repository,
    ) {
    }

    public function handle(int $colorId): ColorData
    {
        $color = $this->repository->find($colorId);

        if (! $color) {
            throw new ModelNotFoundException();
        }

        return ColorData::fromModel($color);
    }
}

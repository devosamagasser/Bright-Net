<?php

namespace App\Modules\Taxonomy\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Taxonomy\Application\DTOs\{ColorData, ColorInput};
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;

class UpdateColorUseCase
{
    public function __construct(
        private readonly ColorRepositoryInterface $repository,
    ) {
    }

    public function handle(int $colorId, ColorInput $input): ColorData
    {
        $color = $this->repository->find($colorId);

        if (! $color) {
            throw new ModelNotFoundException();
        }

        $color = $this->repository->update(
            $color,
            attributes: $input->attributes,
            translations: $input->translations,
        );

        return ColorData::fromModel($color->load('translations'));
    }
}

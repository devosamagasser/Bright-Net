<?php

namespace App\Modules\Taxonomy\Application\UseCases;

use App\Modules\Taxonomy\Application\DTOs\{ColorData, ColorInput};
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;

class CreateColorUseCase
{
    public function __construct(
        private readonly ColorRepositoryInterface $repository,
    ) {
    }

    public function handle(ColorInput $input): ColorData
    {
        $color = $this->repository->create(
            attributes: $input->attributes,
            translations: $input->translations,
        );

        return ColorData::fromModel($color->load('translations'));
    }
}

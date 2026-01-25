<?php

namespace App\Modules\Specifications\Application\UseCases;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;

class GetDraftSpecificationUseCase
{
    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
    ) {
    }

    public function handle(Model $user): Specification
    {
        return $this->specifications->getOrCreateDraft($user);
    }
}



<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationInput;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;

class UpdateSpecificationUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
    ) {
    }

    public function handle(Specification $specification, SpecificationInput $input, int $companyId): Specification
    {
        $this->assertEditable($specification, $companyId);
        return $this->specifications->update(
            $specification,
            $input->attributes()
        );
    }
}



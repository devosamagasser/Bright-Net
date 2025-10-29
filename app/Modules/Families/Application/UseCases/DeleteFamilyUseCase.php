<?php

namespace App\Modules\Families\Application\UseCases;

use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class DeleteFamilyUseCase
{
    public function __construct(private readonly FamilyRepositoryInterface $families)
    {
    }

    public function handle(Family $family): void
    {
        $this->families->delete($family);
    }
}

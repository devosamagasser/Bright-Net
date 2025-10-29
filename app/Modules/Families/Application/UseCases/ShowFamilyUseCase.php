<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Families\Application\DTOs\FamilyData;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class ShowFamilyUseCase
{
    public function __construct(private readonly FamilyRepositoryInterface $families)
    {
    }

    public function handle(int $familyId): FamilyData
    {
        $family = $this->families->find($familyId);

        if ($family === null) {
            throw new ModelNotFoundException();
        }

        return FamilyData::fromModel($family);
    }
}

<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Models\Family;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\ProductGroup;
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;

class CutPasteProductGroupUseCase
{
    public function __construct(
        private readonly ProductGroupRepositoryInterface $groups,
    ) {
    }

    public function handle(ProductGroup $group, int $family_id): ProductGroup
    {
        $family = $this->requireFamily($family_id);
        $this->assertFamilyBelongsToSubcategory($family, $group);

        $updatedGroup = $this->groups->cutPasteProduct($group, $family_id);

        return $updatedGroup;
    }

    private function requireFamily(int $familyId): Family
    {
        $family = Family::query()->find($familyId);

        if ($family === null) {
            throw ValidationException::withMessages([
                'family_id' => trans('validation.exists', ['attribute' => 'family']),
            ]);
        }

        return $family;
    }

    private function assertFamilyBelongsToSubcategory(Family $family, ProductGroup $group): void
    {
        if ($group->subcategory_id !== null && $family->subcategory_id !== $group->subcategory_id) {
            throw ValidationException::withMessages([
                'family_id' => trans('validation.in', ['attribute' => 'family']),
            ]);
        }
    }
}


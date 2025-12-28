<?php

namespace App\Modules\Families\Application\UseCases;

use App\Modules\Families\Application\DTOs\{FamilyData, FamilyInput};
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class ChangeOrderUserCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $families,
    ) {
    }

    public function handle(Family $family, Family $familyBefore)
    {
        if($family->subcategory_id !== $familyBefore->subcategory_id) {
            return;
        }
        
        $this->families->changeOrder(
            $family,
            $familyBefore
        );
    }

}

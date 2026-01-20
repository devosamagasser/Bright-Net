<?php

namespace App\Modules\Favourites\Domain\Services;

use App\Models\Supplier;
use App\Modules\Brands\Application\DTOs\BrandData;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Departments\Application\DTOs\DepartmentData;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionData;
use App\Modules\Subcategories\Application\DTOs\SubcategoryData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RelationModelService
{
    public static function modelQuery(Model $relationModel, $ids, int $perPage = 15): LengthAwarePaginator
    {
        $query = $relationModel->newQuery()
            ->whereIn($relationModel->getKeyName(), $ids);

        $query = match (get_class($relationModel)) {
            Supplier::class => $query->with(['company']),
            Brand::class => $query->with(['media']),
            Department::class => $query->with(['media']),
            default => $query,
        };

        return $query->paginate($perPage);
    }

    public function modelData($relationModel, Collection $relationCollection)
    {
        return match ($relationModel) {
            'supplier' => CompanyData::collection(
                $relationCollection
                    ->loadMissing('company')
                    ->pluck('company')
                    ->filter()
                    ->unique('id')
                    ->values(),
                withProfile: false
            ),
            'solution' => SolutionData::collection($relationCollection),
            'brand'    => BrandData::collection($relationCollection),
            'department' => DepartmentData::collection($relationCollection),
            'subcategory' => SubcategoryData::collection($relationCollection),
        };
    }
}

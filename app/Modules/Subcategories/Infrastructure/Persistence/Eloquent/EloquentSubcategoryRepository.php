<?php

namespace App\Modules\Subcategories\Infrastructure\Persistence\Eloquent;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class EloquentSubcategoryRepository implements SubcategoryRepositoryInterface
{
    use HandlesTranslations;

    public function paginate(int $perPage = 15, ?int $departmentId = null): LengthAwarePaginator
    {
        $query = $this->query()->with('department');

        if ($departmentId !== null) {
            $query->where('department_id', $departmentId);
        }

        return $query->orderByDesc('id')->paginate($perPage);
    }

    public function find(int $id): ?Subcategory
    {
        return $this->query()->with('department')->find($id);
    }

    public function create(array $attributes, array $translations): Subcategory
    {
        $subcategory = new Subcategory();
        $this->fillSubcategory($subcategory, $attributes, $translations);

        return $subcategory;
    }

    public function update(Subcategory $subcategory, array $attributes, array $translations): Subcategory
    {
        $this->fillSubcategory($subcategory, $attributes, $translations);

        return $subcategory;
    }

    public function delete(Subcategory $subcategory): void
    {
        $subcategory->delete();
    }

    protected function query(): Builder
    {
        return Subcategory::query();
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    protected function fillSubcategory(Subcategory $subcategory, array $attributes, array $translations): void
    {
        $subcategory->fill($attributes);
        $this->fillTranslations($subcategory, $translations);
        $subcategory->save();
    }
}

<?php

namespace App\Modules\Departments\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Departments\Domain\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    use HandlesTranslations;

    /**
     * @inheritDoc
     */
    public function paginate(int $perPage = 15, int $solutionId): LengthAwarePaginator
    {
        return $this->query()->where('solution_id', $solutionId)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): ?Department
    {
        return $this->query()->with('subcategories')->find($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes, array $translations): Department
    {
        $department = new Department();

        $this->fillDepartment($department, $attributes, $translations);

        return $department;
    }

    /**
     * @inheritDoc
     */
    public function update(Department $department, array $attributes, array $translations): Department
    {
        $this->fillDepartment($department, $attributes, $translations);

        return $department;
    }

    /**
     * @inheritDoc
     */
    public function delete(Department $department): void
    {
        $department->delete();
    }

    /**
     * Base query builder instance.
     */
    protected function query(): Builder
    {
        return Department::query();
    }

    /**
     * Shared logic between create and update operations.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    protected function fillDepartment(Department $department, array $attributes, array $translations): void
    {
        $department->fill($attributes);
        $this->fillTranslations($department, $translations);
        $department->save();
    }
}

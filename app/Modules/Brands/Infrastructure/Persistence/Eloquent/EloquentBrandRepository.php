<?php

namespace App\Modules\Brands\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Brand
    {
        return $this->query()->find($id);
    }

    public function create(array $attributes, array $solutions): Brand
    {
        return DB::transaction(function () use ($attributes, $solutions) {
            $brand = new Brand();
            $brand->fill($attributes);
            $brand->save();

            $this->syncRelations($brand, $solutions);

            return $brand->loadMissing(['region', 'solutions', 'departments']);
        });
    }

    public function update(Brand $brand, array $attributes, array $solutions): Brand
    {
        return DB::transaction(function () use ($brand, $attributes, $solutions) {
            $brand->fill($attributes);
            $brand->save();

            $this->syncRelations($brand, $solutions);

            return $brand->loadMissing(['region', 'solutions', 'departments']);
        });
    }

    public function delete(Brand $brand): void
    {
        $brand->delete();
    }

    protected function query(): Builder
    {
        return Brand::query()->with(['region', 'solutions', 'departments']);
    }

    /**
     * @param  array<int, array{solution_id:int, departments:array<int, int>}>  $solutions
     */
    protected function syncRelations(Brand $brand, array $solutions): void
    {
        $solutionIds = Collection::make($solutions)
            ->pluck('solution_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $departmentIds = Collection::make($solutions)
            ->flatMap(static fn (array $solution) => $solution['departments'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $brand->solutions()->sync($solutionIds);
        $brand->departments()->sync($departmentIds);
    }
}

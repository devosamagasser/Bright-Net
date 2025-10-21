<?php

namespace App\Modules\Brands\Infrastructure\Persistence\Eloquent;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filter = []): LengthAwarePaginator
    {
        return $this->query()
            ->with(['region'])
            ->filter($filter)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Brand
    {
        return $this->query()->with(['region', 'solutions', 'departments'])->find($id);
    }

    public function create(array $attributes, array $solutions, UploadedFile $logo): Brand
    {
        return $this->fillBrand(new Brand(), $attributes, $solutions, $logo);
    }

    public function update(Brand $brand, array $attributes, array $solutions, ?UploadedFile $logo = null): Brand
    {
        return $this->fillBrand(new Brand(), $attributes, $solutions, $logo);
    }

    public function delete(Brand $brand): void
    {
        $brand->delete();
    }

    protected function query(): Builder
    {
        return Brand::query();
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


    /**
     * Shared logic between create and update operations.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    protected function fillBrand(Brand $brand, array $attributes, $solutions, $logo = null)
    {
        return DB::transaction(function () use ($brand, $attributes, $solutions, $logo) {
            $brand->fill($attributes);
            if ($logo) {
                $brand->addMedia($logo)->toMediaCollection('logo');
            }
            $brand->save();

            $this->syncRelations($brand, $solutions);

            return $brand->loadMissing(['region', 'solutions', 'departments']);
        });
    }
}

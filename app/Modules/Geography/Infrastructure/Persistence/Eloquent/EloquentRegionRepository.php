<?php

namespace App\Modules\Geography\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Geography\Domain\Models\Region;
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;

class EloquentRegionRepository implements RegionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    public function find(int $id): ?Region
    {
        return $this->query()->find($id);
    }

    public function create(array $attributes): Region
    {
        $region = new Region();
        $this->fillRegion($region, $attributes);

        return $region;
    }

    public function update(Region $region, array $attributes): Region
    {
        $this->fillRegion($region, $attributes);

        return $region;
    }

    public function delete(Region $region): void
    {
        $region->delete();
    }

    protected function query(): Builder
    {
        return Region::query();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function fillRegion(Region $region, array $attributes): void
    {
        $region->fill($attributes);
        $region->save();
    }
}

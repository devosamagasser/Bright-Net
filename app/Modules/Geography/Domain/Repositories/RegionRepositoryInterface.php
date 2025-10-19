<?php

namespace App\Modules\Geography\Domain\Repositories;

use App\Modules\Geography\Domain\Models\Region;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RegionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Region;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Region;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Region $region, array $attributes): Region;

    public function delete(Region $region): void;
}

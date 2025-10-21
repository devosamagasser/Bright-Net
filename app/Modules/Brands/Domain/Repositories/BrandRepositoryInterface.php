<?php

namespace App\Modules\Brands\Domain\Repositories;

use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface BrandRepositoryInterface
{
    /**
     * Paginate available brands with their relations.
     */
    public function paginate(int $perPage = 15, array $filter): LengthAwarePaginator;

    /**
     * Retrieve a brand by its primary key.
     */
    public function find(int $id): ?Brand;

    /**
     * Persist a new brand along with its relations.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{solution_id:int, departments:array<int, int>}>  $solutions
     */
    public function create(array $attributes, array $solutions, UploadedFile $logo): Brand;

    /**
     * Update an existing brand.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{solution_id:int, departments:array<int, int>}>  $solutions
     */
    public function update(Brand $brand, array $attributes, array $solutions, ?UploadedFile $logo = null): Brand;

    /**
     * Delete a brand from storage.
     */
    public function delete(Brand $brand): void;
}

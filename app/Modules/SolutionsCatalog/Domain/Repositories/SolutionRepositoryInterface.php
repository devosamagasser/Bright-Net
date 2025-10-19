<?php

namespace App\Modules\SolutionsCatalog\Domain\Repositories;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SolutionRepositoryInterface
{
    /**
     * Paginate available solutions with their translations and relations.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve a solution by its primary key, eager loading relations.
     */
    public function find(int $id): ?Solution;

    /**
     * Persist a new solution with the provided attributes and translated names.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, string> $translations
     */
    public function create(array $attributes, array $translations): Solution;

    /**
     * Update an existing solution.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, string> $translations
     */
    public function update(Solution $solution, array $attributes, array $translations): Solution;

    /**
     * Delete a solution from storage.
     */
    public function delete(Solution $solution): void;
}

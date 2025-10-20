<?php

namespace App\Modules\Taxonomy\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Taxonomy\Domain\Models\Color;

interface ColorRepositoryInterface
{
    public function paginate(int $perPage = 100): LengthAwarePaginator;

    public function find(int $id): ?Color;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function create(array $attributes, array $translations): Color;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function update(Color $color, array $attributes, array $translations): Color;

    public function delete(Color $color): void;
}

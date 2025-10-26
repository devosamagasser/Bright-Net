<?php

namespace App\Modules\DataSheets\Domain\Repositories;

use App\Modules\DataSheets\Domain\Models\DataTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Modules\DataSheets\Application\DTOs\DataFieldInput;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

interface DataTemplateRepositoryInterface
{
    /**
     * Create a new data template with its translatable fields.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, DataFieldInput>  $fields
     */
    public function create(array $attributes, array $translations, array $fields): DataTemplate;

    /**
     * Retrieve paginated data templates with their fields.
     */
    public function paginate(int $perPage = 15, ?DataTemplateType $type = null): LengthAwarePaginator;

    /**
     * Find a template by its identifier including related fields.
     */
    public function find(int $id, ?DataTemplateType $type = null): ?DataTemplate;

    /**
     * Retrieve all templates that belong to a specific subcategory.
     */
    public function getBySubcategory(int $subcategoryId, ?DataTemplateType $type = null): Collection;

    /**
     * Find the template for a given subcategory/type combination.
     */
    public function findBySubcategoryAndType(int $subcategoryId, DataTemplateType $type): ?DataTemplate;

    /**
     * Update an existing template and sync its fields.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, DataFieldInput>  $fields
     */
    public function update(DataTemplate $template, array $attributes, array $translations, array $fields): DataTemplate;

    /**
     * Delete the given template.
     */
    public function delete(DataTemplate $template): void;
}

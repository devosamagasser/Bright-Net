<?php

namespace App\Modules\DataSheets\Domain\Repositories;

use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Application\DTOs\DataFieldInput;

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
}

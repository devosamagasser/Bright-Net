<?php

namespace App\Modules\Departments\Presentation\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Subcategories\Presentation\Resources\SubcategoryResource;

class DepartmentResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'solution_id' => $this->solution_id ?? $this->solutionId,
            'subcategories' => $this->transformSubcategories(),
            'translations' => $this->transformTranslations(),
            'created_at' => $this->resolveDate('created_at', 'createdAt'),
            'updated_at' => $this->resolveDate('updated_at', 'updatedAt'),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function transformSubcategories(): array
    {
        $subcategories = $this->resource->subcategories ?? null;

        if (method_exists($this->resource, 'relationLoaded') && $this->resource->relationLoaded('subcategories')) {
            $subcategories = $this->resource->subcategories;
        }

        if ($subcategories instanceof Collection) {
            $subcategories = $subcategories->all();
        }

        if (! is_array($subcategories)) {
            return [];
        }

        return collect($subcategories)
            ->map(fn ($subcategory) => (new SubcategoryResource($subcategory))->toArray(request()))
            ->values()
            ->all();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function transformTranslations(): array
    {
        $translations = $this->translations ?? [];

        if ($translations instanceof Collection) {
            $translations = $translations->all();
        }

        return collect($translations)->mapWithKeys(static function ($translation, $key) {
            if (is_array($translation)) {
                return [$key => $translation];
            }

            if (is_object($translation) && property_exists($translation, 'locale')) {
                return [$translation->locale => ['name' => $translation->name]];
            }

            return [];
        })->all();
    }

    protected function resolveDate(string $snakeCase, string $camelCase): ?string
    {
        $value = $this->{$snakeCase} ?? null;

        if ($value instanceof Carbon) {
            return $value->toISOString();
        }

        $camel = $this->{$camelCase} ?? null;

        if ($camel instanceof Carbon) {
            return $camel->toISOString();
        }

        if (is_string($camel)) {
            return $camel;
        }

        return null;
    }
}

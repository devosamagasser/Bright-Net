<?php

namespace App\Modules\Subcategories\Presentation\Resources;

use Illuminate\Support\{Carbon, Collection};
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
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
            'department_id' => $this->department_id ?? $this->departmentId,
            'translations' => $this->when(
                request()->is('*/subcategories/*') && request()->method() === 'GET',
                $this->transformTranslations()
            ),
        ];
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

}

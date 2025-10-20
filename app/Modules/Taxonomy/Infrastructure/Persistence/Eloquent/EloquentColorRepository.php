<?php

namespace App\Modules\Taxonomy\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Taxonomy\Domain\Models\Color;
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;

class EloquentColorRepository implements ColorRepositoryInterface
{
    use HandlesTranslations;

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    public function find(int $id): ?Color
    {
        return $this->query()->find($id);
    }

    public function create(array $attributes, array $translations): Color
    {
        $color = new Color();
        $this->fillColor($color, $attributes, $translations);

        return $color;
    }

    public function update(Color $color, array $attributes, array $translations): Color
    {
        $this->fillColor($color, $attributes, $translations);

        return $color;
    }

    public function delete(Color $color): void
    {
        $color->delete();
    }

    protected function query(): Builder
    {
        return Color::query();
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    protected function fillColor(Color $color, array $attributes, array $translations): void
    {
        $color->fill($attributes);
        $this->fillTranslations($color, $translations);
        $color->save();
    }
}

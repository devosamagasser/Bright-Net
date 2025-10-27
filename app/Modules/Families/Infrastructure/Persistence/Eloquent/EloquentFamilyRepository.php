<?php

namespace App\Modules\Families\Infrastructure\Persistence\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Shared\Support\Traits\HandlesTranslations;

class EloquentFamilyRepository implements FamilyRepositoryInterface
{
    use HandlesTranslations;

    public function paginate(int $perPage, int $subcategoryId, ?int $supplierId = null): LengthAwarePaginator
    {
        return $this->query()
            ->where('subcategory_id', $subcategoryId)
            ->when($supplierId, fn (Builder $query) => $query->where('supplier_id', $supplierId))
            ->latest('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Family
    {
        return $this->query()->find($id);
    }

    public function create(array $attributes, array $translations, array $values): Family
    {
        return DB::transaction(function () use ($attributes, $translations, $values) {
            $family = new Family();
            $family->fill($attributes);
            $this->fillTranslations($family, $translations);
            $family->save();

            $this->syncValues($family, $values);

            return $family->load($this->relations());
        });
    }

    public function update(Family $family, array $attributes, array $translations, ?array $values = null): Family
    {
        return DB::transaction(function () use ($family, $attributes, $translations, $values) {
            $family->fill($attributes);
            $this->fillTranslations($family, $translations);
            $family->save();

            if ($values !== null) {
                $family->fieldValues()->delete();
                $this->syncValues($family, $values);
            }

            return $family->load($this->relations());
        });
    }

    public function delete(Family $family): void
    {
        $family->delete();
    }

    protected function query(): Builder
    {
        return Family::query()->with($this->relations());
    }

    /**
     * @param  array<int, array{data_field_id:int, value:mixed}>  $values
     */
    protected function syncValues(Family $family, array $values): void
    {
        if (empty($values)) {
            return;
        }

        $family->fieldValues()->createMany($values);
    }

    /**
     * @return array<int, string>
     */
    protected function relations(): array
    {
        return [
            'translations',
            'fieldValues.field.translations',
            'media',
        ];
    }
}

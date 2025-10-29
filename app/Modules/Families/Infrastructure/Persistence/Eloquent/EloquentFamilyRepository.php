<?php

namespace App\Modules\Families\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class EloquentFamilyRepository implements FamilyRepositoryInterface
{
    use HandlesTranslations;

    public function create(array $attributes, array $translations, array $values): Family
    {
        return DB::transaction(function () use ($attributes, $translations, $values): Family {
            $family = new Family();
            $family->fill($attributes);
            $this->fillTranslations($family, $translations);
            $family->save();

            $this->syncFieldValues($family, $values, true);

            return $this->loadAggregates($family);
        });
    }

    public function update(Family $family, array $attributes, array $translations, array $values): Family
    {
        return DB::transaction(function () use ($family, $attributes, $translations, $values): Family {
            $currentTemplateId = (int) $family->data_template_id;
            $templateChanged = array_key_exists('data_template_id', $attributes)
                && (int) $attributes['data_template_id'] !== $currentTemplateId;

            if ($attributes !== []) {
                $family->fill($attributes);
            }

            if ($translations !== []) {
                $this->fillTranslations($family, $translations);
            }

            $family->save();

            if ($templateChanged || $values !== []) {
                $this->syncFieldValues($family, $values, $templateChanged);
            }

            return $this->loadAggregates($family);
        });
    }

    public function delete(Family $family): void
    {
        DB::transaction(static function () use ($family): void {
            $family->delete();
        });
    }

    public function find(int $id): ?Family
    {
        return Family::query()
            ->with(['translations', 'fieldValues.field.translations'])
            ->find($id);
    }

    public function getBySubcategory(int $subcategoryId, ?int $supplierId = null): Collection
    {
        return Family::query()
            ->with(['translations', 'fieldValues.field.translations'])
            ->when($supplierId !== null, static function ($query) use ($supplierId): void {
                $query->where('supplier_id', $supplierId);
            })
            ->where('subcategory_id', $subcategoryId)
            ->orderBy('name')
            ->get();
    }

    private function loadAggregates(Family $family): Family
    {
        return $family->load(['translations', 'fieldValues.field.translations']);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function syncFieldValues(Family $family, array $values, bool $overwriteMissing): void
    {
        $template = DataTemplate::query()
            ->with('fields')
            ->find($family->data_template_id);

        if ($template === null) {
            return;
        }

        $fields = $template->fields->keyBy('name');
        $retainedFieldIds = [];

        foreach ($values as $key => $value) {
            if (! $fields->has($key)) {
                continue;
            }

            /** @var DataField $field */
            $field = $fields->get($key);
            $normalizedValue = $this->prepareValue($field, $value);

            $familyFieldValue = $family->fieldValues()
                ->updateOrCreate(
                    [
                        'data_field_id' => $field->getKey(),
                    ],
                    [
                        'value' => $normalizedValue,
                    ],
                );

            $retainedFieldIds[] = (int) $familyFieldValue->data_field_id;
        }

        if ($overwriteMissing) {
            $query = $family->fieldValues();

            if ($retainedFieldIds !== []) {
                $query->whereNotIn('data_field_id', $retainedFieldIds);
            }

            $query->delete();
        }
    }

    private function prepareValue(DataField $field, mixed $value): mixed
    {
        $type = $field->type;

        return match ($type) {
            DataFieldType::MULTISELECT => array_values(Arr::wrap($value)),
            DataFieldType::BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            DataFieldType::NUMBER => is_numeric($value) ? $value + 0 : $value,
            default => $value,
        };
    }
}

<?php

namespace App\Modules\DataSheets\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Application\DTOs\DataFieldInput;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class EloquentDataTemplateRepository implements DataTemplateRepositoryInterface
{
    use HandlesTranslations;

    /**
     * @param  array<int, DataFieldInput>  $fields
     */
    public function create(array $attributes, array $translations, array $fields): DataTemplate
    {
        return DB::transaction(function () use ($attributes, $translations, $fields) {
            $template = new DataTemplate();
            $template->fill($attributes);
            $this->fillTranslations($template, $translations);
            $template->save();

            $fieldRecords = [];
            foreach ($fields as $fieldInput) {
                $field = new DataField([
                    'data_template_id' => $template->getKey(),
                ]);

                $field->fill(array_merge(
                    $fieldInput->attributes,
                    ['data_template_id' => $template->getKey()],
                ));

                $this->fillTranslations($field, $fieldInput->translations);
                $field->save();

                $fieldRecords[] = compact('field', 'fieldInput');
            }

            $this->syncFieldDependencies($fieldRecords);

            return $template->load(['fields.dependency.dependsOnField']);
        });
    }

    public function find(int $id, ?DataTemplateType $type = null): ?DataTemplate
    {
        $query = DataTemplate::query()
            ->with(['fields.dependency.dependsOnField'])
            ->whereKey($id);

        if ($type) {
            $query->where('type', $type->value);
        }

        return $query->first();
    }

    public function getBySubcategory(int $subcategoryId, ?DataTemplateType $type = null): Collection
    {
        return DataTemplate::query()
            ->with(['fields.dependency.dependsOnField'])
            ->where('subcategory_id', $subcategoryId)
            ->latest('id')
            ->when($type, fn ($query) =>
                $query->where('type', $type->value)
            )->get();
    }

    public function findBySubcategoryAndType(int $subcategoryId, DataTemplateType $type): ?DataTemplate
    {
        return DataTemplate::query()
            ->with(['fields.dependency.dependsOnField'])
            ->where('subcategory_id', $subcategoryId)
            ->where('type', $type->value)
            ->first();
    }

    public function update(DataTemplate $template, array $attributes, array $translations, array $fields): DataTemplate
    {
        return DB::transaction(function () use ($template, $attributes, $translations, $fields) {
            $template->fill($attributes);
            $this->fillTranslations($template, $translations);
            $template->save();

            $existingFields = $template->fields()->get()->keyBy('id');
            $retainedFieldIds = [];
            $fieldRecords = [];

            foreach ($fields as $fieldInput) {
                $fieldAttributes = array_merge(
                    $fieldInput->attributes,
                    ['data_template_id' => $template->getKey()],
                );

                if ($fieldInput->id && $existingFields->has($fieldInput->id)) {
                    /** @var DataField $field */
                    $field = $existingFields->get($fieldInput->id);
                    $field->fill($fieldAttributes);
                    $this->fillTranslations($field, $fieldInput->translations);
                    $field->save();

                    $retainedFieldIds[] = $field->getKey();
                    $fieldRecords[] = compact('field', 'fieldInput');
                    continue;
                }

                $field = new DataField();
                $field->fill($fieldAttributes);
                $this->fillTranslations($field, $fieldInput->translations);
                $field->save();

                $retainedFieldIds[] = $field->getKey();
                $fieldRecords[] = compact('field', 'fieldInput');
            }

            $fieldsQuery = $template->fields();
            if (! empty($retainedFieldIds)) {
                $fieldsQuery->whereNotIn('id', $retainedFieldIds);
            }

            $fieldsQuery->get()->each->delete();

            $this->syncFieldDependencies($fieldRecords);

            return $template->load(['fields.dependency.dependsOnField']);
        });
    }

    public function delete(DataTemplate $template): void
    {
        DB::transaction(static function () use ($template): void {
            $template->delete();
        });
    }

    /**
     * @param  array<int, array{field: DataField, fieldInput: DataFieldInput}>  $fieldRecords
     */
    private function syncFieldDependencies(array $fieldRecords): void
    {
        if ($fieldRecords === []) {
            return;
        }

        $fieldsByName = collect($fieldRecords)
            ->mapWithKeys(function (array $record) {
                /** @var DataField $field */
                $field = $record['field'];

                return [$field->name => $field];
            });

        foreach ($fieldRecords as $record) {
            /** @var DataField $field */
            $field = $record['field'];
            /** @var DataFieldInput $input */
            $input = $record['fieldInput'];

            $dependency = $input->dependency;

            if ($dependency === null) {
                $field->dependency()->delete();
                continue;
            }

            $dependsOnField = $fieldsByName->get($dependency->field);

            if (! $dependsOnField) {
                continue;
            }

            $field->dependency()->updateOrCreate(
                [
                    'data_field_id' => $field->getKey(),
                ],
                [
                    'depends_on_field_id' => $dependsOnField->getKey(),
                    'values' => $dependency->values,
                ],
            );
        }
    }
}

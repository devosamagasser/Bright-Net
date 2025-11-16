<?php

namespace App\Modules\DataSheets\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Application\DTOs\{DataFieldInput, DependedFieldInput};
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

                $this->syncFieldDependencies($field, $fieldInput->dependencies);
            }

            return $template->load(['fields.dependencies.dependsOnField']);
        });
    }

    public function find(int $id, ?DataTemplateType $type = null): ?DataTemplate
    {
        $query = DataTemplate::query()
            ->with(['fields.dependencies.dependsOnField'])
            ->whereKey($id);

        if ($type) {
            $query->where('type', $type->value);
        }

        return $query->first();
    }

    public function getBySubcategory(int $subcategoryId, ?DataTemplateType $type = null): Collection
    {
        return DataTemplate::query()
            ->with(['fields.dependencies.dependsOnField'])
            ->where('subcategory_id', $subcategoryId)
            ->latest('id')
            ->when($type, fn ($query) =>
                $query->where('type', $type->value)
            )->get();
    }

    public function findBySubcategoryAndType(int $subcategoryId, DataTemplateType $type): ?DataTemplate
    {
        return DataTemplate::query()
            ->with(['fields.dependencies.dependsOnField'])
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

                    $this->syncFieldDependencies($field, $fieldInput->dependencies);

                    $retainedFieldIds[] = $field->getKey();
                    continue;
                }

                $field = new DataField();
                $field->fill($fieldAttributes);
                $this->fillTranslations($field, $fieldInput->translations);
                $field->save();

                $this->syncFieldDependencies($field, $fieldInput->dependencies);

                $retainedFieldIds[] = $field->getKey();
            }

            $fieldsQuery = $template->fields();
            if (! empty($retainedFieldIds)) {
                $fieldsQuery->whereNotIn('id', $retainedFieldIds);
            }

            $fieldsQuery->get()->each->delete();

            return $template->load(['fields.dependencies.dependsOnField']);
        });
    }

    public function delete(DataTemplate $template): void
    {
        DB::transaction(static function () use ($template): void {
            $template->delete();
        });
}

    /**
     * @param  array<int, DependedFieldInput>  $dependencies
     */
    private function syncFieldDependencies(DataField $field, array $dependencies): void
    {
        $existing = $field->dependencies()->get()->keyBy('id');
        $retained = [];

        foreach ($dependencies as $dependencyInput) {
            $attributes = [
                'data_field_id' => $field->getKey(),
                'depends_on_field_id' => $dependencyInput->dependsOnFieldId,
                'values' => $dependencyInput->values,
            ];

            if ($dependencyInput->id && $existing->has($dependencyInput->id)) {
                $dependency = $existing->get($dependencyInput->id);
                $dependency->fill($attributes);
                $dependency->save();

                $retained[] = $dependency->getKey();
                continue;
            }

            $dependency = $field->dependencies()->create($attributes);
            $retained[] = $dependency->getKey();
        }

        if (! empty($retained)) {
            $field->dependencies()->whereNotIn('id', $retained)->delete();
        } else {
            $field->dependencies()->delete();
        }
    }
}

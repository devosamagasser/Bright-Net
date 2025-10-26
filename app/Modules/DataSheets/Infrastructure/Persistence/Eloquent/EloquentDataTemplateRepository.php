<?php

namespace App\Modules\DataSheets\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\DB;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Application\DTOs\DataFieldInput;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

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
            }

            return $template->load(['fields']);
        });
    }
}

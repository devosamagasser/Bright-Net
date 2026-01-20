<?php
namespace App\Modules\Products\Domain\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;


class ProductFieldValueSyncService
{
        /**
     * @param  array<string, mixed>  $values
     */
    public function syncFieldValues(DataTemplate $template = null, Product $product, array $values): void
    {
        if ($template === null) {
            return;
        }
        $product->fieldValues()->delete();

        $fields = $template->fields->keyBy('name');
        $rows = [];

        foreach ($values as $key => $value) {
            if (! $fields->has($key)) {
                continue;
            }

            $field = $fields->get($key);

            $rows[] = [
                'product_id'    => $product->getKey(),
                'data_field_id' => $field->getKey(),
                'value'         => $this->prepareValue($field, $value),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }
        // 3️⃣ bulk insert
        if ($rows !== []) {
            DB::table('product_field_values')->insert($rows);
        }
    }

    private function prepareValue(DataField $field, mixed $value): mixed
    {
        $type = $field->type;

        return match ($type) {
            DataFieldType::MULTISELECT => json_encode(array_values(Arr::wrap($value))),
            DataFieldType::BOOLEAN => json_encode(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)),
            DataFieldType::NUMBER => json_encode(is_numeric($value) ? $value + 0 : $value),
            default => json_encode($value),
        };
    }
}

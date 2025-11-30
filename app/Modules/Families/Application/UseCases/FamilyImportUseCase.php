<?php

namespace App\Modules\Families\Application\UseCases;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class FamilyImportUseCase implements ToModel, WithHeadingRow
{
    public $supplierId;
    public $subcategoryId;
    private FamilyRepositoryInterface $families;

    public function __construct($supplierId, $subcategoryId, FamilyRepositoryInterface $families)
    {
        $this->supplierId = $supplierId;
        $this->subcategoryId = $subcategoryId;
        $this->families = $families;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $input = $this->prepareData($row);

        return $this->families->create(
            $input['attributes'],
            $input['translations'],
            $input['values']
        );
    }

    public function prepareData(array $data)
    {
        $attributes['name'] = $data['name'];
        $attributes['supplier_id'] = $this->supplierId;
        $attributes['subcategory_id'] = $this->subcategoryId;

        $translations['ar'] = [
            'description' => $data['description_ar'],
        ];
        $translations['en'] = [
            'description' => $data['description_en'],
        ];

        return [
            'attributes' => $attributes,
            'translations' => $translations,
            'values' => [],
        ];
    }
}


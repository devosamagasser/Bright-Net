<?php

namespace App\Modules\Products\Application\UseCases;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Modules\Families\Domain\Models\Family;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ProductImportUseCase implements ToModel, WithHeadingRow
{
    public Family $family;
    private ProductRepositoryInterface $product;

    public function __construct(Family $family, ProductRepositoryInterface $product)
    {
        $this->family = $family;
        $this->product = $product;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $input = $this->prepareData($row);

        return $this->product->create(
            $input['attributes'],
            $input['translations'],
            $input['values']
        );
    }

    public function prepareData(array $data)
    {
        $dataTemplate = DataTemplate::select('id')->where('subcategory_id', $this->family->subcategory_id)
                                ->where('type', DataTemplateType::PRODUCT)
                                ->first();
        $attributes = [
            'family_id' => $this->family->id,
            'data_template_id' => $dataTemplate->id,
            'code' => $data['code'],
            'stock' => $data['stock'],
            'disclaimer' => $data['disclaimer']
        ];

        $translations = [
            'ar' => [
                'name' => $data['name_ar'],
                'description' => $data['description_ar'],
            ],
            'en' => [
                'name' => $data['name_ar'],
                'description' => $data['description_en'],
            ]
        ];

        return [
            'attributes' => $attributes,
            'translations' => $translations,
            'values' => [],
        ];
    }

    public function rules(): array
    {
        return [
            '*.code' => 'required|string|unique:products,code',
            '*.stock' => 'nullable|numeric',
        ];
    }

}


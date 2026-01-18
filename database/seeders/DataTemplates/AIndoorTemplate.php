<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class AIndoorTemplate
{
    public function build(int $subcategoryId)
    {

        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Indoor Datasheet Template',
                'description' => 'Specification template for indoor lighting products'
            ],
            'ar' => [
                'name' => 'قالب بيانات داخلي',
                'description' => 'قالب المواصفات الفنية لمنتجات الإضاءة الداخلية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — RECOMMENDED APPLICATIONS
        |--------------------------------------------------------------------------
        */
        $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'recommended_applications',
            'position' => 1,
            'group' => 'Physical',
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                [
                    'label' => 'Office & Corporate',
                    'value' => 'Office & Corporate',
                ],
                [
                    'label' => 'Retail & Showroom',
                    'value' => 'Retail & Showroom',
                ],
                [
                    'label' => 'Hospitality (Hotels, Restaurants)',
                    'value' => 'Hospitality (Hotels, Restaurants)',
                ],
                [
                    'label' => 'Residential & Living',
                    'value' => 'Residential & Living',
                ],
                [
                    'label' => 'Culture (Museums, Galleries)',
                    'value' => 'Culture (Museums, Galleries)',
                ],
                [
                    'label' => 'Education (Schools, Libraries)',
                    'value' => 'Education (Schools, Libraries)',
                ],
                [
                    'label' => 'Healthcare',
                    'value' => 'Healthcare',
                ],
                [
                    'label' => 'Industrial & Logistics',
                    'value' => 'Industrial & Logistics',
                ],
                [
                    'label' => 'Sports (Indoor)',
                    'value' => 'Sports (Indoor)',
                ],
                [
                    'label' => 'Food & Beverage Processing',
                    'value' => 'Food & Beverage Processing',
                ],
                [
                    'label' => 'Pharmaceutical / Cleanroom',
                    'value' => 'Pharmaceutical / Cleanroom',
                ],
            ],
            'en' => ['label' => 'Recommended Applications'],
            'ar' => ['label' => 'الاستخدامات الموصى بها'],
        ]);

        // $template->fields()->create([
        //     'type' => 'multiselect',
        //     'name' => 'type',
        //     'position' => 1,
        //     'group' => 'Physical',
        //     'is_required' => false,
        //     'is_filterable' => false,
        //     'options' => [
        //         'Downlight',
        //         'Spotlight',
        //         'Track Light',
        //         'Linear',
        //         'Pendant',
        //         'Troffer / Panel',
        //         'High Bay',
        //         'Low Bay',
        //         'Wall-Washer',
        //         'Wall-Grazer',
        //         'Wall Mounted (Indoor)',
        //         'LED Flex / Strip',
        //         'Emergency / Exit',
        //         'Fittings & Accessories (Indoor)'
        //     ],
        //     'en' => ['label' => 'Product Type '],
        //     'ar' => ['label' => 'نوع المنتج'],
        // ]);

        (new AGeneralTemplate())->build($template);
    }
}

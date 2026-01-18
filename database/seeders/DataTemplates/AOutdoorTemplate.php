<?php

namespace Database\Seeders\DataTemplates;

use App\Modules\DataSheets\Domain\Models\DataTemplate;

class AOutdoorTemplate
{
    public function build(int $subcategoryId)
    {

        $template = DataTemplate::create([
            'subcategory_id' => $subcategoryId,
            'type' => 'product',
            'en' => [
                'name' => 'Outdoor Datasheet Template',
                'description' => 'Specification template for outdoor lighting products'
            ],
            'ar' => [
                'name' => 'قالب بيانات خارجي',
                'description' => 'قالب المواصفات الفنية لمنتجات الإضاءة الخارجية'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELD — RECOMMENDED APPLICATIONS (OUTDOOR)
        |--------------------------------------------------------------------------
        */
        $applications = $template->fields()->create([
            'type' => 'multiselect',
            'name' => 'recommended_applications',
            'group' => 'Physical',
            'position' => 1,
            'is_required' => false,
            'is_filterable' => false,
            'options' => [
                [
                    'label' => 'Facade & Structure',
                    'value' => 'Facade & Structure',
                ],
                [
                    'label' => 'Landscape & Garden',
                    'value' => 'Landscape & Garden',
                ],
                [
                    'label' => 'Public Spaces & Plazas',
                    'value' => 'Public Spaces & Plazas',
                ],
                [
                    'label' => 'Road & Street',
                    'value' => 'Road & Street',
                ],
                [
                    'label' => 'Tunnel',
                    'value' => 'Tunnel',
                ],
                [
                    'label' => 'Sports & Area',
                    'value' => 'Sports & Area',
                ],
                [
                    'label' => 'Water Features (Pools, Fountains)',
                    'value' => 'Water Features (Pools, Fountains)',
                ],
                [
                    'label' => 'Marine (Saltwater / Coastal)',
                    'value' => 'Marine (Saltwater / Coastal)',
                ],
            ],
            'en' => [
                'label' => 'Recommended Applications',
                'placeholder' => 'Select suitable outdoor use cases',
            ],
            'ar' => [
                'label' => 'الاستخدامات الموصى بها',
                'placeholder' => 'اختر مجالات الاستخدام الخارجية',
            ],
        ]);

        // $template->fields()->create([
        //     'type' => 'multiselect',
        //     'name' => 'type',
        //     'position' => 1,
        //     'group' => 'Physical',
        //     'is_required' => false,
        //     'is_filterable' => false,
        //     'options' => [
        //         'Floodlight',
        //         'Wall Pack',
        //         'Bollard',
        //         'In-Ground',
        //         'Streetlight',
        //         'Post Top',
        //         'Downlight (IP Rated)',
        //         'Spotlight (IP Rated)',
        //         'Linear (IP Rated)',
        //         'Pendant (IP Rated)',
        //         'Wall-Washer (IP Rated)',
        //         'Wall-Grazer (IP Rated)',
        //         'Wall Mounted (Outdoor)',
        //         'LED Flex / Strip (IP Rated)',
        //         'Emergency / Exit',
        //         'Fittings & Accessories (Outdoor)',
        //     ],
        //     'en' => ['label' => 'Product Type'],
        //     'ar' => ['label' => 'نوع المنتج'],
        // ]);

        (new AGeneralTemplate())->build($template);
    }
}

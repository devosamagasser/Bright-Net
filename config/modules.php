<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Domain Modules
    |--------------------------------------------------------------------------
    |
    | Each module is represented by a service provider class that encapsulates
    | its bindings, routes, and infrastructure concerns. The order influences
    | the sequence in which providers are registered at runtime.
    |
    */
    'providers' => [
        \App\Modules\SolutionsCatalog\Infrastructure\Providers\SolutionsCatalogServiceProvider::class,
        \App\Modules\Departments\Infrastructure\Providers\DepartmentsServiceProvider::class,
        \App\Modules\Subcategories\Infrastructure\Providers\SubcategoriesServiceProvider::class,
        \App\Modules\Brands\Infrastructure\Providers\BrandsServiceProvider::class,
        \App\Modules\Geography\Infrastructure\Providers\GeographyServiceProvider::class,
        \App\Modules\Companies\Infrastructure\Providers\CompaniesServiceProvider::class,
        \App\Modules\SupplierEngagements\Infrastructure\Providers\SupplierEngagementsServiceProvider::class,
        \App\Modules\Taxonomy\Infrastructure\Providers\TaxonomyServiceProvider::class,
        \App\Modules\AccessControl\Infrastructure\Providers\AccessControlServiceProvider::class,
        \App\Modules\Authentication\Infrastructure\Providers\AuthenticationServiceProvider::class,
        \App\Modules\DataSheets\Infrastructure\Providers\DataSheetsServiceProvider::class,
        \App\Modules\Families\Infrastructure\Providers\FamiliesServiceProvider::class,
    ],
];


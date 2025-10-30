<?php

namespace App\Modules\Products\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Infrastructure\Persistence\Eloquent\EloquentProductRepository;

class ProductsServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class,
        );
    }

    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/products.php'));
    }
}

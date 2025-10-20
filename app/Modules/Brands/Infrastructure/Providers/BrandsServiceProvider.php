<?php

namespace App\Modules\Brands\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;
use App\Modules\Brands\Infrastructure\Persistence\Eloquent\EloquentBrandRepository;

class BrandsServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            BrandRepositoryInterface::class,
            EloquentBrandRepository::class,
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
            ->group(base_path('routes/modules/brands.php'));
    }
}

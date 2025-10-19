<?php

namespace App\Modules\Geography\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;
use App\Modules\Geography\Infrastructure\Persistence\Eloquent\EloquentRegionRepository;

class GeographyServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            RegionRepositoryInterface::class,
            EloquentRegionRepository::class,
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
            ->group(base_path('routes/modules/regions.php'));
    }
}

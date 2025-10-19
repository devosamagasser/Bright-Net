<?php

namespace App\Modules\Taxonomy\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;
use App\Modules\Taxonomy\Infrastructure\Persistence\Eloquent\EloquentColorRepository;

class TaxonomyServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            ColorRepositoryInterface::class,
            EloquentColorRepository::class,
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
            ->group(base_path('routes/modules/colors.php'));
    }
}

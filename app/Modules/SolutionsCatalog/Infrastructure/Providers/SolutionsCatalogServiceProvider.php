<?php

namespace App\Modules\SolutionsCatalog\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use App\Modules\SolutionsCatalog\Infrastructure\Persistence\Eloquent\EloquentSolutionRepository;
use Illuminate\Support\Facades\Route;

class SolutionsCatalogServiceProvider extends AbstractModuleServiceProvider
{
    /**
     * @inheritDoc
     */
    protected function registerBindings(): void
    {
        $this->app->bind(
            SolutionRepositoryInterface::class,
            EloquentSolutionRepository::class
        );
    }

    /**
     * @inheritDoc
     */
    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/solutions.php'));
    }
}

<?php

namespace App\Modules\Specifications\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\Specifications\Infrastructure\Persistence\Eloquent\EloquentSpecificationRepository;

class SpecificationsServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            SpecificationRepositoryInterface::class,
            EloquentSpecificationRepository::class,
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
            ->group(base_path('routes/modules/specifications.php'));
    }
}



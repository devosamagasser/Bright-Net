<?php

namespace App\Modules\Families\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use App\Modules\Families\Infrastructure\Persistence\Eloquent\EloquentFamilyRepository;

class FamiliesServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            FamilyRepositoryInterface::class,
            EloquentFamilyRepository::class,
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
            ->group(base_path('routes/modules/families.php'));
    }
}

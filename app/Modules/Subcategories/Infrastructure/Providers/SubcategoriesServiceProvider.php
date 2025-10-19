<?php

namespace App\Modules\Subcategories\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;
use App\Modules\Subcategories\Infrastructure\Persistence\Eloquent\EloquentSubcategoryRepository;

class SubcategoriesServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            SubcategoryRepositoryInterface::class,
            EloquentSubcategoryRepository::class,
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
            ->group(base_path('routes/modules/subcategories.php'));
    }
}

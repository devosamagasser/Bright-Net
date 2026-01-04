<?php

namespace App\Modules\Favourites\Infrastructure\Providers;

use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use App\Modules\Favourites\Infrastructure\Persistence\Eloquent\EloquentCollectionRepository;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use Illuminate\Support\Facades\Route;

class FavouritesServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            CollectionRepositoryInterface::class,
            EloquentCollectionRepository::class,
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
            ->group(base_path('routes/modules/favourites.php'));
    }
}


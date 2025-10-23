<?php

namespace App\Modules\AccessControl\Infrastructure\Providers;

use App\Modules\AccessControl\Application\Contracts\TokenIssuerInterface;
use App\Modules\AccessControl\Application\Services\SanctumTokenIssuer;
use App\Modules\AccessControl\Domain\Repositories\{
    CompanyUserRepositoryInterface,
    PlatformUserRepositoryInterface
};
use App\Modules\AccessControl\Infrastructure\Persistence\Eloquent\{
    EloquentCompanyUserRepository,
    EloquentPlatformUserRepository
};
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use Illuminate\Support\Facades\Route;

class AccessControlServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(PlatformUserRepositoryInterface::class, EloquentPlatformUserRepository::class);
        $this->app->bind(CompanyUserRepositoryInterface::class, EloquentCompanyUserRepository::class);
        $this->app->bind(TokenIssuerInterface::class, SanctumTokenIssuer::class);
    }

    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/access-control.php'));
    }
}

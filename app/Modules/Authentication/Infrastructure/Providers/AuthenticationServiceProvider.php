<?php

namespace App\Modules\Authentication\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Authentication\Domain\Types\UserCompany;
use App\Modules\Authentication\Domain\Types\UserPlatform;
use App\Modules\Authentication\Domain\Types\UserTypeFactory;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;

class AuthenticationServiceProvider extends AbstractModuleServiceProvider
{

    protected function registerServices(): void
    {
        $this->app->singleton(UserTypeFactory::class, function ($app) {
            return new UserTypeFactory([
                $app->make(UserCompany::class),
                $app->make(UserPlatform::class),
            ]);
        });
    }

    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/authentication.php'));
    }
}


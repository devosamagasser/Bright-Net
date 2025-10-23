<?php

namespace App\Modules\SupplierEngagements\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;

class SupplierEngagementsServiceProvider extends AbstractModuleServiceProvider
{
    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/supplier-engagements.php'));
    }
}


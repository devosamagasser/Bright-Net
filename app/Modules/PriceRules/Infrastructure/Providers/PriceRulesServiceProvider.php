<?php

namespace App\Modules\PriceRules\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\PriceRules\Infrastructure\Persistence\Eloquent\EloquentPriceRulesRepository;

class PriceRulesServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            PriceRulesRepositoryInterface::class,
            EloquentPriceRulesRepository::class,
        );
    }

    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        \Illuminate\Support\Facades\Route::middleware(['api', 'locale'])
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/modules/price-rules.php'));
    }
}

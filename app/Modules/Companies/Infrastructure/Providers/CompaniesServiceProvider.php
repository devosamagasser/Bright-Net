<?php

namespace App\Modules\Companies\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Companies\Domain\Profiles\SupplierProfile;
use App\Modules\Companies\Domain\Profiles\CompanyProfileFactory;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Infrastructure\Persistence\Eloquent\EloquentCompanyRepository;

class CompaniesServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            CompanyRepositoryInterface::class,
            EloquentCompanyRepository::class,
        );
    }

    protected function registerServices(): void
    {
        $this->app->singleton(CompanyProfileFactory::class, function ($app) {
            return new CompanyProfileFactory([
                $app->make(SupplierProfile::class),
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
            ->group(base_path('routes/modules/companies.php'));
    }
}


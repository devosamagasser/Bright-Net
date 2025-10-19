<?php

namespace App\Modules\Departments\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;
use App\Modules\Departments\Infrastructure\Persistence\Eloquent\EloquentDepartmentRepository;

class DepartmentsServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            DepartmentRepositoryInterface::class,
            EloquentDepartmentRepository::class,
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
            ->group(base_path('routes/modules/departments.php'));
    }
}

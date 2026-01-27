<?php

namespace App\Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Base service provider for all domain modules. Modules can extend this class
 * to keep registration and bootstrapping concerns consistent.
 */
abstract class AbstractModuleServiceProvider extends ServiceProvider
{
    /**
     * Register all container bindings for the module.
     */
    public function register(): void
    {
        $this->registerBindings();
        $this->registerServices();
    }

    /**
     * Bootstrap the module once all services are registered.
     */
    public function boot(): void
    {
        $this->bootConfigurations();
        $this->bootRoutes();
        $this->bootListeners();
    }

    /**
     * Register interfaces to implementations.
     */
    protected function registerBindings(): void
    {
        // Intended to be overridden by modules that bind interfaces.
    }

    /**
     * Register any shared services or singletons.
     */
    protected function registerServices(): void
    {
        // Intended to be overridden by modules that expose services.
    }

    /**
     * Publish or merge configuration files.
     */
    protected function bootConfigurations(): void
    {
        // Intended to be overridden by modules that need configuration.
    }

    /**
     * Register HTTP or console routes.
     */
    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['api', 'locale', 'auth:sanctum', 'supplier'])
            ->prefix('api/easy-access')
            ->name('api.')
            ->group(base_path('routes/modules/easy-access.php'));
    }

    /**
     * Register event/listener bindings or observers.
     */
    protected function bootListeners(): void
    {
        // Intended to be overridden by modules that listen to events.
    }
}


<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register the application's module providers.
     */
    public function register(): void
    {
        foreach ($this->providers() as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function providers(): array
    {
        return config('modules.providers', []);
    }
}

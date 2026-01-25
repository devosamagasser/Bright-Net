<?php

namespace App\Modules\SpecificationLogs\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;

class SpecificationLogsServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        // No bindings yet; uses Specification module repositories.
    }

    protected function bootRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        // Routes are already loaded via routes/modules/specifications.php
    }
}



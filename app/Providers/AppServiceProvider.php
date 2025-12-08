<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Modules\Quotations\Domain\Models\QuotationProduct;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\App\Modules\ModulesServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fail fast on N+1 in non-production environments
        if (! app()->environment('production')) {
            Model::preventLazyLoading();
        }
    }
}

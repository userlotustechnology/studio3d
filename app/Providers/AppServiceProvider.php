<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar locale do Carbon para português do Brasil
        Carbon::setLocale(config('app.locale'));
        Date::setLocale(config('app.locale'));
        
        // Garantir que o timezone seja sempre o configurado
        date_default_timezone_set(config('app.timezone'));
    }
}

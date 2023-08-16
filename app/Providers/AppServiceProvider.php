<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

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

    //Funcion para el formato de fecha
    public function boot(): void
    {
        Carbon::macro('toFormattedDate', function()
        {
            return $this->format('d-m-y');
        });
    }
}

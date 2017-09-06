<?php

namespace App\Modules\Logistics;

use Illuminate\Support\ServiceProvider;

class LogisticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('logistics', function () {
            return new LogisticsModule();
        });
    }
}

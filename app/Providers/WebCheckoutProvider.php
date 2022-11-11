<?php

namespace App\Providers;

use App\web_checkout\Register;
use Illuminate\Support\ServiceProvider;

class WebCheckoutProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('webCheckout', WebCheckout::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

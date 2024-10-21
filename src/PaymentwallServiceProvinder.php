<?php

namespace PaymentwallLaravel;

use Illuminate\Support\ServiceProvider;

class PaymentwallServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paymentwall.php', 'paymentwall');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__. '/../config/paymentwall.php' => config_path('paymentwall.php'),
        ], 'config');


        // Test router
        $this->app['router']->get('test-paymentwall', function() {
            return 'Paymentwall Laravel Package is working';
        });
    }
}
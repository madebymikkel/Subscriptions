<?php

namespace MadeByMikkel\Subscriptions;

use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider {
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot () {
        $this->publishes([
            __DIR__ . '/../config/subscriptions.php' => config_path('subscriptions.php'),
        ], 'subscriptions-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register () {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/subscriptions.php',
            'subscriptions'
        );
    }
}

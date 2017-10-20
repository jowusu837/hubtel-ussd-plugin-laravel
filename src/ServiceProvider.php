<?php

namespace Jowusu837\HubtelUssd;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $publishPath = config_path('hubtel-ussd.php');
        } else {
            $publishPath = base_path('config/hubtel-ussd.php');
        }
        $this->publishes([$this->configPath() => $publishPath], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'hubtel-ussd');
    }

    protected function configPath() {
        return __DIR__ . '/../config/hubtel-ussd.php';
    }

}
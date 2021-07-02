<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelBootstrapComponentSelect2Provider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bs');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        //$this->publishes(
            //[__DIR__ . '/../../config/laravel-bootstrap-components.php' => config_path('laravel-bootstrap-components.php')],
            //['laravel-bootstrap-components', 'laravel-bootstrap-components:config']
        //);

        $this->publishes(
            [__DIR__ . '/../../resources/views' => resource_path('views/vendor/bs')],
            ['laravel-bootstrap-components', 'laravel-bootstrap-components:views']
        );
    }

    public function register()
    {
        //$this->mergeConfigFrom(__DIR__ . '/../../config/laravel-bootstrap-components.php', 'laravel-bootstrap-components');
    }
}

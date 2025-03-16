<?php

namespace Bagisto\Vies\Providers;

use Illuminate\Support\ServiceProvider;

class ViesServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadTranslationsFrom(dirname(__DIR__).'/Resources/lang', 'vies');

        $this->publishes([__DIR__.'/Config/vies.php' => config_path('vies.php')], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/Config/vies.php', 'vies');

        $this->mergeConfigFrom(dirname(__DIR__).'/Config/system.php', 'core');

        $this->app->register(EventServiceProvider::class);
    }
}

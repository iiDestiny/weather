<?php

/*
 * This file is part of the iidestiny/weather.
 *
 * (c) 罗彦 <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace IiDestiny\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('services.weather.ak'), config('services.weather.sn'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/weather.php' => config_path('weather.php'),
        ]);
    }
}

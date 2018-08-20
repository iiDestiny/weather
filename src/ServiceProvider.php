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

        $this->mergeConfigFrom([
            __DIR__ . '../config/services.php' => config_path('services.php'),
        ]);
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}

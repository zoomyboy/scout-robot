<?php

namespace App\Providers;

use App\Conf;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('setting', function() {
            return new class {
                public function get($key) {
                    return Conf::first()->{$key};
                }
            };
        });
    }
}

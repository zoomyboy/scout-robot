<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class NaMiServiceProvider extends ServiceProvider
{
    public function register()
	{
		$this->app->bind('nami.member', function() {
			return new \App\Services\NaMi\Member();
		});

		$this->app->bind('nami', function() {
			return new \App\Services\NaMi\NaMiService();
		});
    }

    public function boot()
    {
        parent::boot();
    }
}

<?php

namespace App\Providers;

use App\Nami\Interfaces\UserResolver;
use App\Nami\Resolvers\CurrentUser;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class NaMiServiceProvider extends ServiceProvider
{
    public function register()
	{
        $this->app->bind(UserResolver::class, function() {
            return new CurrentUser();
        });

		$this->app->singleton('nami.member', function() {
			return app(\App\Services\Nami\Member::class);
		});

		$this->app->singleton('nami.region', function() {
			return app(\App\Services\Nami\Region::class);
		});

		$this->app->singleton('nami.country', function() {
			return app(\App\Services\Nami\Country::class);
		});

		$this->app->singleton('nami.group', function() {
			return app(\App\Services\Nami\Group::class);
		});

		$this->app->singleton('nami.membership', function() {
			return app(\App\Services\Nami\Membership::class);
		});

		$this->app->singleton('nami.nationality', function() {
			return app(\App\Services\Nami\Nationality::class);
		});

		$this->app->singleton('nami', function() {
			return app(\App\Nami\Service::class);
		});
    }

    public function boot()
    {
        parent::boot();
    }
}

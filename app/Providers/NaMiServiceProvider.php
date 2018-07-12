<?php

namespace App\Providers;

use App\NaMi\Interfaces\UserResolver;
use App\NaMi\Resolvers\CurrentUser;
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
			return app(\App\Services\NaMi\Member::class);
		});

		$this->app->singleton('nami.region', function() {
			return app(\App\Services\NaMi\Region::class);
		});

		$this->app->singleton('nami.country', function() {
			return app(\App\Services\NaMi\Country::class);
		});

		$this->app->singleton('nami.group', function() {
			return app(\App\Services\NaMi\Group::class);
		});

		$this->app->singleton('nami.membership', function() {
			return app(\App\Services\NaMi\Membership::class);
		});

		$this->app->singleton('nami.nationality', function() {
			return app(\App\Services\NaMi\Nationality::class);
		});

		$this->app->singleton('nami', function() {
			return app(\App\Services\NaMi\NaMiService::class);
		});
    }

    public function boot()
    {
        parent::boot();
    }
}

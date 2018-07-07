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
			return new \App\Services\NaMi\Member();
		});

		$this->app->singleton('nami.region', function() {
			return new \App\Services\NaMi\Region();
		});

		$this->app->singleton('nami.country', function() {
			return new \App\Services\NaMi\Country();
		});

		$this->app->singleton('nami.group', function() {
			return new \App\Services\NaMi\Group();
		});

		$this->app->singleton('nami.membership', function() {
			return new \App\Services\NaMi\Membership();
		});

		$this->app->singleton('nami.nationality', function() {
			return new \App\Services\NaMi\Nationality();
		});

		$this->app->singleton('nami', function() {
			return new \App\Services\NaMi\NaMiService();
		});
    }

    public function boot()
    {
        parent::boot();
    }
}

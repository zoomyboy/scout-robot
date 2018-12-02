<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\Traits\SeedsDatabase;
use Tests\Traits\MocksSetting;
use Tests\Traits\SetsUpNaMi;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\HandlesApiCalls;
use Zoomyboy\Tests\Traits\HandlesExceptions;

class IntegrationTestCase extends \Tests\TestCase
{
    use CreatesModels;
    use HandlesExceptions;
    use AuthenticatesUsers;
    use MocksSetting;
    use HandlesApiCalls;
    use DatabaseMigrations;
    use SetsUpNaMi;
    use SeedsDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();

        if (!property_exists($this, 'dontFakeNotifications')) {
            Notification::fake();
        }

        if (!property_exists($this, 'dontFakeEvents')) {
            Event::fake();
        }
    }

    public function afterAuthUserCreated($user)
    {
        \App\Right::get()->each(function ($right) use ($user) {
            $user->usergroup->rights()->attach($right);
        });
    }

    public function seedForMember() {
        $this->runSeeder('GenderSeeder');
        factory(\App\Country::class)->create();
        factory(\App\Region::class)->create(['is_null' => false]);
        factory(\App\Confession::class)->create();
        factory(\App\Way::class)->create();
        factory(\App\Nationality::class)->create();
        $this->runSeeder('UsergroupSeeder');
    }
}

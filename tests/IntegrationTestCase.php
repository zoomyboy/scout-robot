<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
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

    /** @todo in Trait auslagern */
    public function createPayment(\App\Member $member, $values)
    {
        $member->payments()->save(
            (new \App\Payment([
                'nr' => $values['nr']
            ]))
            ->subscription()->associate(\App\Subscription::find($values['subscription']))
            ->status()->associate(\App\Status::find($values['status']))
        );
    }
}

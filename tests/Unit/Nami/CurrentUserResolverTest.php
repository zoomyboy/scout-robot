<?php

namespace Tests\Unit\Nami;

use App\NaMi\Interfaces\UserResolver;
use App\NaMi\Resolvers\CurrentUser;
use Tests\UnitTestCase;

class CurrentUserResolverTest extends UnitTestCase {
    /** @test */
    public function it_gets_the_current_user_from_the_settings_model() {
        $this->setting('namiUser', 'pille');
        $this->setting('namiEnabled', true);

        $resolver = app(CurrentUser::class);
        $this->assertEquals('pille', $resolver->getUsername());
    }

    /** @test */
    public function it_gets_the_current_password_from_the_settings_model() {
        $this->setting('namiPassword', 'frf');
        $this->setting('namiEnabled', true);

        $resolver = app(CurrentUser::class);
        $this->assertEquals('frf', $resolver->getPassword());
    }

    /** @test */
    public function it_gets_the_current_group_from_the_settings_model() {
        $this->setting('namiGroup', '8888');
        $this->setting('namiEnabled', true);

        $resolver = app(CurrentUser::class);
        $this->assertEquals('8888', $resolver->getGroup());
    }

    /** @test */
    public function it_returns_false_when_nami_import_is_disabled() {
        $this->setting('namiEnabled', false);

        $resolver = app(CurrentUser::class);
        $this->assertFalse($resolver->getPassword());
        $this->assertFalse($resolver->getUsername());
        $this->assertFalse($resolver->getGroup());
    }


     /** @test */
    public function the_credentials_are_missing_when_nami_is_disabled() {
        $this->setting('namiEnabled', false);

        $resolver = app(CurrentUser::class);
        $this->assertFalse($resolver->hasCredentials());
    }

     /** @test */
    public function the_credentials_are_missing_when_password_or_user_arent_set() {
        $this->settings('namiUser', ['pw', '']);
        $this->settings('namiPassword', ['', 'a']);
        $this->setting('namiEnabled', true);

        $resolver = app(CurrentUser::class);
        $this->assertFalse($resolver->hasCredentials());
        $this->assertFalse($resolver->hasCredentials());
    }
}

<?php

namespace Tests\Unit\Nami;

use App\Nami\Interfaces\UserResolver;
use App\Nami\Resolvers\CurrentUser;
use App\Nami\Resolvers\InlineUser;
use Tests\UnitTestCase;

class InlineUserReceiverTest extends UnitTestCase {
    /** @test */
    public function it_gets_the_current_user_from_the_settings_model() {
        $resolver = new InlineUser('pille', '', 0);
        $this->assertEquals('pille', $resolver->getUsername());
    }

    /** @test */
    public function it_gets_the_current_password_from_the_settings_model() {
        $this->setting('namiPassword', 'frf');
        $this->setting('namiEnabled', true);

        $resolver = new InlineUser('', 'frf', 0);
        $this->assertEquals('frf', $resolver->getPassword());
    }

    /** @test */
    public function it_gets_the_current_group_from_the_settings_model() {
        $resolver = new InlineUser('', 'frf', 8888);
        $this->assertEquals('8888', $resolver->getGroup());
    }

    /** @test */
    public function it_doesnt_care_about_enabled_nami_import() {
        $this->setting('namiEnabled', false);

        $resolver = new InlineUser('a', 'frf', 8888);
        $this->assertEquals('frf', $resolver->getPassword());
        $this->assertEquals('a', $resolver->getUsername());
        $this->assertEquals(8888, $resolver->getGroup());
    }

     /** @test */
    public function the_credentials_are_missing_when_password_or_user_arent_set() {
        $resolver = new InlineUser('a', '', 8888);
        $this->assertFalse($resolver->hasCredentials());

        $resolver = new InlineUser('', 'a', 8888);
        $this->assertFalse($resolver->hasCredentials());
    }
}

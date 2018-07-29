<?php

namespace Tests\Integration\Nami;

use Tests\Integration\NamiTestCase;
use \Mockery as M;
use App\Nami\Jobs\UpdateMember;
use App\Nami\Manager\Member as MemberManager;

class JobPushMemberTest extends NamiTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
    }

    /** @test */
    public function it_pushes_to_the_member_manager_when_the_job_executes() {
        $member = $this->create('Member', ['nami_id' => 12]);

        $manager = M::mock(MemberManager::class);
        $manager->shouldReceive('push')->withArgs(function($arg) use ($member) {
            return $arg->id == $member->id;
        })->once()->andReturnNull();
        $this->app->instance(MemberManager::class, $manager);

        dispatch(new UpdateMember($member));
    }
}

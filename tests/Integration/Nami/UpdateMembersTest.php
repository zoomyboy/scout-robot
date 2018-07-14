<?php

namespace Tests\Integration\NaMi;

use Tests\IntegrationTestCase;
use App\Facades\NaMi\NaMi;
use App\Facades\NaMi\NaMiMember;
use App\Jobs\UpdateNaMiMember;
use App\Member;

class UpdateMembersTest extends IntegrationTestCase {
    public function setUp() {
        parent::setUp();

        $this->runSeeder('DatabaseSeeder');
        $this->setUpNaMi();
    }

    /** @test */
    public function it_updates_a_members_firstname_in_nami() {
        NaMi::createMember(['id' => '1234', 'vorname' => 'Hans']);

        $m = NaMiMember::single(1234);
        $this->assertEquals('Hans', $m->vorname);

        $localMember = $this->create('Member', ['firstname' => 'Hans2', 'nami_id' => 1234]);

        UpdateNaMiMember::dispatch($localMember, $localMember);

        $m = NaMiMember::single(1234);
        $this->assertEquals('Hans2', $m->vorname);
    }
}

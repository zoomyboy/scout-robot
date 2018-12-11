<?php

namespace App\Integration\NaMi;

use App\Member;
use Tests\FeatureTestCase;
use App\Nami\Jobs\UpdateMember;
use App\Facades\NaMi\NaMiMember;
use Illuminate\Support\Facades\Queue;

class UpdateMembersTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->seed(\UsergroupSeeder::class);

		Queue::fake();

		$this->authAsApi();
	}

	/** @test */
	public function it_synchs_a_nami_member_when_it_is_updated() {
        $this->withExceptionHandling();

        \Setting::set('namiEnabled', true);
		$member = $this->create('Member', ['nami_id' => 3362]);

		$data = $this->patchApi("member/$member->id", $this->values($member))
		    ->assertSuccess();

		Queue::assertPushed(UpdateMember::class, function($j) {
			return $j->member->nami_id == 3362;
		});
	}

	/** @test */
	public function it_doesnt_update_a_nami_member_when_nami_is_disabled() {
        $this->withExceptionHandling();

        \Setting::set('namiEnabled', false);
        $member = $this->create('Member', ['nami_id' => 3362]);

        $data = $this->patchApi("member/$member->id", $this->values($member))
            ->assertSuccess();

        Queue::assertNotPushed(UpdateMember::class);
	}

	/** @test */
	public function it_doesnt_update_a_nami_member_when_nami_id_is_not_set() {
        $this->withExceptionHandling();

        \Setting::set('namiEnabled', false);
        $member = $this->create('Member', ['nami_id' => null]);

        $data = $this->patchApi("member/$member->id", $this->values($member))
            ->assertSuccess();

        Queue::assertNotPushed(UpdateMember::class);
	}

    public function values($member, $overrides = []) {
        return array_merge([
            'firstname' => $member->firstname,
            'lastname' => $member->lastname,
            'birthday' => $member->birthday->format('Y-m-d'),
            'joined_at' => $member->joined_at->format('Y-m-d'),
            'address' => $member->address,
            'zip' => $member->zip,
            'city' => $member->city,
            'country' => $member->country_id,
            'way' => $member->way_id,
            'email' => $member->email,
            'nationality' => $member->nationality_id
        ], $overrides);
    }
}

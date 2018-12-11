<?php

namespace Tests\Feature\Member;

use Tests\FeatureTestCase;

class IndexTest extends FeatureTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();

        $this->runSeeder('UsergroupSeeder');
    }

    /** @test */
    public function it_gets_all_members() {
        $this->authAsApi();

        $this->create('Member', $this->values());

        $this->getApi('member/table')
            ->assertSuccess()
            ->assertJson([[
                'firstname' => 'Krug',
                'lastname' => 'Betty',
                'address' => 'Stefanstr 22',
                'zip' => '4875',
                'city' => 'Paderborn',
                'joined_at' => '2012-02-06 00:00:00',
                'id' => 1
            ]]);
    }

    /** @test */
    public function it_appends_the_members_strikes() {
        $this->authAsApi();

        $member = $this->create('Member', $this->values());

        $member->payments()->create([
            'nr' => '22',
            'subscription_id' => 1,
            'status_id' => 1
        ]);

        $member->payments()->create([
            'nr' => '23',
            'subscription_id' => 2,
            'status_id' => 2
        ]);

        $this->getApi('member/table')
            ->assertSuccess()
            ->assertJson([[
                'strikes' => 9000,
                'id' => 1
            ]]);
    }

    /** @test */
    public function it_gets_no_members_when_not_authed() {
        $this->withExceptionHandling();

        $this->getApi('member/table')
            ->assertUnauthorized();
    }

    public function values($overrides = []) {
        return array_merge([
            'firstname' => 'Krug',
            'lastname' => 'Betty',
            'address' => 'Stefanstr 22',
            'zip' => '4875',
            'city' => 'Paderborn',
            'joined_at' => '2012-02-06',
        ], $overrides);
    }
}

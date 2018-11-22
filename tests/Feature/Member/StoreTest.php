<?php

namespace App\Feature\Member;

use Setting;
use App\Facades\NaMi\NaMiMember;
use App\Jobs\StoreNaMiMember;
use App\Member;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\NamiTestCase;

class StoreTest extends NamiTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->setUpActivityGroups();

        $this->runSeeder('UsergroupSeeder');
		Queue::fake();
		$this->authAsApi();
	}

	/** @test */
	public function it_stores_a_member() {
		Setting::set('namiEnabled', true);

		$this->postApi('member', $this->data())
			->assertSuccess();

        $this->assertDatabaseHas('members', $this->databaseData());
        $this->assertDatabaseHas('memberships', [
            'member_id' => 1,
            'group_id' => 1,
            'activity_id' => 1
        ]);

		Queue::assertPushed(StoreNaMiMember::class, function($j) {
			return $j->member->firstname == 'Philipp';
		});
	}

    /** @test */
    public function it_must_not_enter_an_email_address_when_the_way_is_post() {
        $this->withExceptionHandling();

        $this->postApi('member', $this->data(['email' => '', 'email_parents' => '', 'way' => 2]))
            ->assertSuccess();

        $this->postApi('member', $this->data(['email' => '', 'email_parents' => '', 'way' => 1]))
            ->assertValidationFailedWith('email', 'email_parents');

        $this->postApi('member', $this->data(['email' => 'philipp@a.de', 'email_parents' => '', 'way' => 1]))
            ->assertSuccess();

        $this->postApi('member', $this->data(['email' => '', 'email_parents' => 'p@a.de', 'way' => 1]))
            ->assertSuccess();
    }

	/** @test */
	public function it_doesnt_sync_a_nami_member_when_nami_is_disabled() {
		Setting::set('namiEnabled', false);

		$this->postApi('member', $this->data())->assertSuccess();

		Queue::assertNotPushed(StoreNaMiMember::class);
	}

	/** @test */
	public function it_doesnt_sync_a_nami_member_when_nami_id_is_set() {
        Setting::set('namiEnabled', true);

        $this->postApi('member', $this->data(['nami_id' => 999]))->assertSuccess();

        Queue::assertNotPushed(StoreNaMiMember::class);
	}

    public function data($overwrites = []) {
        return array_merge([
            'firstname' => 'Philipp',
            'lastname' => 'Lang',
            'birthday' => '2018-02-03',
            'joined_at' => '2018-01-01',
            'address' => 'Strasse',
            'zip' => '42121',
            'city' => 'Sg',
            'keepdata' => true,
            'sendnewspaper' => true,
            'way_id' => 1,
            'gender_id' => 1,
            'country_id' => 1,
            'activity_id' => 1,
            'group_id' => 1,
            'nationality_id' => 1,
            'subscription_id' => 1,
            'confession_id' => 1
        ], $overwrites);
    }

    public function databaseData($overwrites = []) {
        $data = $this->data($overwrites);

        return collect($data)->map(function($value, $row) {
            if (in_array($row, ['birthday', 'joined_at'])) {
                $value .= ' 00:00:00';
            }

            if (in_array($row, ['keepdata', 'sendnewspaper'])) {
                $value = $value ? '1' : '0';
            }

            if (in_array($row, ['way_id', 'gender_id', 'country_id', 'nationality_id'])) {
                $value = (string) $value;
            }

            if ($row == 'activity_id') {return false;}

            return $value;
        })->filter(function($v, $k) {
            return !in_array($k, ['activity_id', 'group_id']);
        })->toArray();
    }
}


<?php

namespace Tests\Unit\NaMi;

use Tests\UnitTestCase;
use App\Facades\NaMi\NaMi;

class CreateMembersTest extends UnitTestCase {

	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('ActivitySeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');
		$this->setUpNaMi();

		$this->config = \App\Conf::first();
	}

	/** @test */
	public function it_receives_a_member_create_post() {
		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => 2,
			'ersteTaetigkeitId' => 35,
			'ersteUntergliederungId' => 4,
			'geschlechtId' => 19
		]);

		$this->assertTrue($response->success);
	}

	/** @test */
	public function it_has_to_enter_a_valid_activity() {
		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => 2,
			'ersteTaetigkeitId' => null,
			'ersteUntergliederungId' => 4,
			'geschlechtId' => 19
		]);
		$this->assertFalse($response->success);

		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => 2,
			'ersteTaetigkeitId' => 666,
			'ersteUntergliederungId' => 4,
			'geschlechtId' => 19
		]);
		$this->assertFalse($response->success);
	}

	/** @test */
	public function it_must_not_enter_a_group() {
		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => 2,
			'ersteTaetigkeitId' => 35,
			'ersteUntergliederungId' => null,
			'geschlechtId' => 19
		]);
		$this->assertTrue($response->success);
	}

	/** @test */
	public function it_has_to_enter_a_subscription_when_using_an_activity_that_requires_payment() {
		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => null,
			'ersteTaetigkeitId' => 6,
			'ersteUntergliederungId' => 4,
			'geschlechtId' => 19
		]);
		$this->assertFalse($response->success);

		$response = NaMi::post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$this->config->namiGroup, [
			'vorname' => 'Max',
			'nachnane' => 'Mustermann',
			'eintrittsdatum' => '2018-01-02T00:00:00',
			'beitragsartId' => 2,
			'ersteTaetigkeitId' => 6,
			'ersteUntergliederungId' => 4,
			'geschlechtId' => 19
		]);
		$this->assertTrue($response->success);
	}
}

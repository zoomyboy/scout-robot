<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ConfigTest extends IntegrationTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('RightSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('UnitSeeder');
		$this->runSeeder('WaySeeder');
	}

	/** @test */
	public function it_sets_all_config_values_in_one_request() {
		$this->authAsApi();

		$this->patchApi('conf/1', [
			'deadlinenr' => 2,
			'deadlineunit' => "4",
			'defaultCountry' => 51,
			'defaultRegion' => 2,
			'default_keepdata' => true,
			'default_sendnewspaper' => true,
			'files' => [],
			'includeFamilies' => true,
			'letterBic' => 'SOL',
			'letterIban' => 'DE44 6666',
			'letterKontoName' => 'Sparkasse',
			'letterZweck' => 'Zweck',
			'letterFrom' => 'Stamm',
			'groupname' => 'Stammu2',
			'personName' => 'Karl',
			'personTel' => '+49 212 1366558',
			'personMail' => 'admin@example.net',
			'personFunction' => 'Kassenwart Stamm AAA',
			'emailHeading' => 'Head',
			'emailBillText' => 'Im Anhang dieser Mail befindet sich die Jahresrechnung für {{ firstname }} {{ lastname }}. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailRememberText' => 'Leider haben wir bisher für die ausstehenden Beträge keinen Zahlungseingang feststellen können. Daher senden wir dir mit dieser E-Mail eine Zahlungserinnerung im Anhang. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailGreeting' => 'Gut Pfad | {{ groupname }}'
		]);

		$this->getApi('info')->assertJson(['conf' => [
			'deadlinenr' => 2,
			'deadlineunit_id' => 4,
			'default_country_id' => "51",
			'default_region_id' => 2,
			'default_keepdata' => true,
			'default_sendnewspaper' => true,
			'files' => [],
			'includeFamilies' => true,
			'letterBic' => 'SOL',
			'letterIban' => 'DE44 6666',
			'letterKontoName' => 'Sparkasse',
			'letterZweck' => 'Zweck',
			'letterFrom' => 'Stamm',
			'groupname' => 'Stammu2',
			'personName' => 'Karl',
			'personTel' => '+49 212 1366558',
			'personMail' => 'admin@example.net',
			'personFunction' => 'Kassenwart Stamm AAA',
			'emailHeading' => 'Head',
			'emailBillText' => 'Im Anhang dieser Mail befindet sich die Jahresrechnung für {{ firstname }} {{ lastname }}. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailRememberText' => 'Leider haben wir bisher für die ausstehenden Beträge keinen Zahlungseingang feststellen können. Daher senden wir dir mit dieser E-Mail eine Zahlungserinnerung im Anhang. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailGreeting' => 'Gut Pfad | {{ groupname }}'
		]]);
	}

	/** @test */
	public function it_cannot_set_configs_when_it_hasnt_right() {
		$this->withExceptionHandling();

		$this->authAsApi();
		auth()->user()->usergroup->rights()->detach(\App\Right::where('title', 'config')->first());

		$this->patchApi('conf/1', [
			'deadlinenr' => 2,
			'deadlineunit' => "4",
			'defaultCountry' => 51,
			'defaultRegion' => 2,
			'default_keepdata' => true,
			'default_sendnewspaper' => true,
			'files' => [],
			'includeFamilies' => true,
			'letterBic' => 'SOL',
			'letterIban' => 'DE44 6666',
			'letterKontoName' => 'Sparkasse',
			'letterZweck' => 'Zweck',
			'groupname' => 'Stammu2'
		])->assertForbidden();
	}
}

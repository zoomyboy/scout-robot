<?php

namespace Tests\Integration;

use App\Nami\Rules\ValidNamiCredentials;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\IntegrationTestCase;
use \Mockery as M;

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

    public function values($overrides = []) {
        return array_merge([
            'deadlinenr' => 2,
            'deadlineunit' => "4",
            'defaultCountry' => 2,
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
        ], $overrides);
    }

    private function noNamiValidationShouldHappen() {
        $namiValidator = M::mock(ValidNamiCredentials::class);
        $namiValidator->shouldReceive('passes')->never();
        $namiValidator->shouldReceive('message')->never();
        $this->app->instance(ValidNamiCredentials::class, $namiValidator);
    }

	/** @test */
	public function it_sets_all_config_values_in_one_request() {
		$this->authAsApi();

		$this->patchApi('conf/1', $this->values());

        $this->noNamiValidationShouldHappen();

		$this->assertDatabaseHas('confs', [
			'deadlinenr' => 2,
			'deadlineunit_id' => 4,
			'default_country_id' => "2",
			'default_region_id' => 2,
			'default_keepdata' => true,
			'default_sendnewspaper' => true,
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
	}

    /** @test */
    public function it_should_enter_a_user_and_a_group_when_enabling_nami() {
        $this->authAsApi();
        $this->withExceptionHandling();
        $this->noNamiValidationShouldHappen();

        \App\Conf::first()->update(['namiEnabled' => false, 'namiGroup' => null, 'namiUser' => null, 'namiPassword' => null]);

        $this->patchApi('conf/1', $this->values([
            'namiEnabled' => true,
            'namiGroup' => '',
            'namiUser' => 901,
            'namiPassword' => 'kaa'
        ]))->assertValidationFailedWith('namiGroup');
        $this->patchApi('conf/1', $this->values([
            'namiEnabled' => true,
            'namiGroup' => 9,
            'namiUser' => '',
            'namiPassword' => 'kaa'
        ]))->assertValidationFailedWith('namiUser');
        $this->patchApi('conf/1', $this->values([
            'namiEnabled' => true,
            'namiGroup' => 'aa',
            'namiUser' => 901,
            'namiPassword' => ''
        ]))->assertValidationFailedWith('namiPassword');
    }

    /** @test */
    public function it_sets_the_nami_user_group_and_password_to_null_when_disabling_nami() {
        $this->authAsApi();
        $this->withExceptionHandling();
        $this->noNamiValidationShouldHappen();

        \App\Conf::first()->update(['namiEnabled' => true, 'namiGroup' => 555, 'namiUser' => 'T', 'namiPassword' => 'A']);

        $this->patchApi('conf/1', $this->values([
            'namiEnabled' => false,
            'namiGroup' => 0,
            'namiUser' => 'a',
            'namiPassword' => 'v'
        ]))->assertSuccess();

        $this->assertDatabaseHas('confs', [
            'namiEnabled' => 0,
            'namiGroup' => null,
            'namiUser' => null,
            'namiPassword' => null
        ]);
    }

    /** @test */
    public function it_updates_configs_when_nami_is_enabled_and_no_password_is_entered() {
        $this->authAsApi();
        $this->withExceptionHandling();
        $this->noNamiValidationShouldHappen();

        \App\Conf::first()->update(['namiEnabled' => true, 'namiGroup' => 555, 'namiUser' => 'T', 'namiPassword' => 'A']);

        $this->patchApi('conf/1', $this->values([
            'namiEnabled' => true,
            'namiGroup' => 5,
            'namiUser' => 'a',
            'namiPassword' => ''
        ]))->assertSuccess();

        $this->assertDatabaseHas('confs', [
            'namiEnabled' => true,
            'namiGroup' => 555,
            'namiUser' => 'T',
            'namiPassword' => 'A'
        ]);
    }
}

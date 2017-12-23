<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;

class ConfigTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runMigration('confs_table');
		$this->runMigration('countries_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('users_table');
		$this->runMigration('rights_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('regions_table');
		$this->runMigration('images_table');
		$this->runMigration('units_table');
		
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('RightSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('UnitSeeder');
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
			'letterZweck' => 'Zweck'
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
			'letterZweck' => 'Zweck'
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
			'letterZweck' => 'Zweck'
		])->assertForbidden();
	}
}

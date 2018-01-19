<?php

namespace Tests\Unit\NaMi;

use Tests\UnitTestCase;
use App\Facades\NaMi\NaMiMembership;
use App\Facades\NaMi\NaMiMember;

class SyncNaMiActivitiesInMember extends UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('ActivitySeeder');
	}

	/** @test */
	public function it_can_sync_the_users_activities_from_nami() {
		$member = $this->create('Member', ['nami_id' => 1520]);

		NaMiMembership::shouldReceive('all')
			->with(1520)
			->andReturn([(object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => 'Administration',
				'entries_aktivVon' => '2016-10-30 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-10-30 19:07:10',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ KassiererIn (20)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 675299,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2016-01-18 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-01-18 23:12:12',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => 'Wölfling',
				'entries_taetigkeit' => '€ LeiterIn (6)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 652912,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object)[
				'entries_aktivBis' => '2016-01-18 00:00:00',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2015-01-01 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2015-02-03 16:00:50',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz) (40)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 623406,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			]]);

		NaMiMembership::shouldReceive('single')
			->with(675299)
			->andReturn((object)[
				'id' => 675299,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ KassiererIn',
				'taetigkeitId' => 20,
				'caeaGroup' => 'Administration',
				'caeaGroupId' => 324,
				'aktivVon' => '2016-10-30 00:00:00',
				'aktivBis' => '',
			]);

		NaMiMembership::shouldReceive('single')
			->with(652912)
			->andReturn((object)[
				'id' => 652912,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ LeiterIn',
				'taetigkeitId' => 6,
				'untergliederung' => 'Wölfling',
				'untergliederungId' => 1,
				'aktivVon' => '2016-01-18 00:00:00',
				'aktivBis' => ''
			]);

		NaMiMembership::shouldReceive('single')
			->with(623406)
			->andReturn((object)[
				'id' => 623406,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz)',
				'taetigkeitId' => 40,
				'aktivVon' => '2015-01-01 00:00:00',
				'aktivBis' => '2016-01-18 00:00:00',
			]);

		NaMiMember::importMemberships($member);

		$this->assertNotNull($member->memberships);
		$memberships = $member->memberships;
		$this->assertCount(1, $memberships);
		$this->assertEquals(8, $memberships->first()->activity->id);
		$this->assertEquals(2, $memberships->first()->group->id);
		$this->assertEquals(652912, $memberships->first()->nami_id);
		$this->assertEquals('2016-01-18', $memberships->first()->created_at->format('Y-m-d'));
	}

	/** @test */
	public function it_doesnt_sync_memberships_that_exist_already() {
		$member = $this->create('Member', ['nami_id' => 1520]);
		$member->memberships()->create([
			'group_id' => 1,
			'activity_id' => 1,
			'nami_id' => 652912,
			'created_at' => '2016-01-16'
		]);
		$this->assertCount(1, $member->memberships);

		NaMiMembership::shouldReceive('all')
			->with(1520)
			->andReturn([(object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => 'Administration',
				'entries_aktivVon' => '2016-10-30 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-10-30 19:07:10',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ KassiererIn (20)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 675299,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2016-01-18 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-01-18 23:12:12',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => 'Wölfling',
				'entries_taetigkeit' => '€ LeiterIn (6)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 652912,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object)[
				'entries_aktivBis' => '2016-01-18 00:00:00',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2015-01-01 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2015-02-03 16:00:50',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz) (40)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 623406,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			]]);

		NaMiMembership::shouldReceive('single')
			->with(675299)
			->andReturn((object)[
				'id' => 675299,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ KassiererIn',
				'taetigkeitId' => 20,
				'caeaGroup' => 'Administration',
				'caeaGroupId' => 324,
				'aktivVon' => '2016-10-30 00:00:00',
				'aktivBis' => '',
			]);

		NaMiMembership::shouldReceive('single')
			->with(652912)
			->andReturn((object)[
				'id' => 652912,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ LeiterIn',
				'taetigkeitId' => 6,
				'untergliederung' => 'Wölfling',
				'untergliederungId' => 1,
				'aktivVon' => '2016-01-18 00:00:00',
				'aktivBis' => ''
			]);

		NaMiMembership::shouldReceive('single')
			->with(623406)
			->andReturn((object)[
				'id' => 623406,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz)',
				'taetigkeitId' => 40,
				'aktivVon' => '2015-01-01 00:00:00',
				'aktivBis' => '2016-01-18 00:00:00',
			]);

		NaMiMember::importMemberships($member);

		$this->assertNotNull($member->memberships);
		$memberships = $member->memberships;
		$this->assertCount(1, $memberships);
		$this->assertEquals(1, $memberships->first()->activity->id);
		$this->assertEquals(1, $memberships->first()->group->id);
		$this->assertEquals('2016-01-16', $memberships->first()->created_at->format('Y-m-d'));
	}

	/** @test */
	public function it_doesnt_sync_unactive_memberships() {
		$member = $this->create('Member', ['nami_id' => 1520]);

		NaMiMembership::shouldReceive('all')
			->with(1520)
			->andReturn([(object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => 'Administration',
				'entries_aktivVon' => '2016-10-30 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-10-30 19:07:10',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ KassiererIn (20)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 675299,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object) [
				'entries_aktivBis' => '2017-01-01 00:00:00',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2016-01-18 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-01-18 23:12:12',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => 'Wölfling',
				'entries_taetigkeit' => '€ LeiterIn (6)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 652912,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object)[
				'entries_aktivBis' => '2016-01-18 00:00:00',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2015-01-01 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2015-02-03 16:00:50',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz) (40)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 623406,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			]]);

		NaMiMembership::shouldReceive('single')
			->with(675299)
			->andReturn((object)[
				'id' => 675299,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ KassiererIn',
				'taetigkeitId' => 20,
				'caeaGroup' => 'Administration',
				'caeaGroupId' => 324,
				'aktivVon' => '2016-10-30 00:00:00',
				'aktivBis' => '',
			]);

		NaMiMembership::shouldReceive('single')
			->with(652912)
			->andReturn((object)[
				'id' => 652912,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ LeiterIn',
				'taetigkeitId' => 6,
				'untergliederung' => 'Wölfling',
				'untergliederungId' => 1,
				'aktivVon' => '2016-01-18 00:00:00',
				'aktivBis' => '2017-01-01 00:00:00'
			]);

		NaMiMembership::shouldReceive('single')
			->with(623406)
			->andReturn((object)[
				'id' => 623406,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz)',
				'taetigkeitId' => 40,
				'aktivVon' => '2015-01-01 00:00:00',
				'aktivBis' => '2016-01-18 00:00:00',
			]);

		NaMiMember::importMemberships($member);

		$this->assertNotNull($member->memberships);
		$memberships = $member->memberships;
		$this->assertCount(0, $memberships);
	}

	/** @test */
	public function it_doesnt_delete_memberships_that_exist_locally() {
		$member = $this->create('Member', ['nami_id' => 1520]);
		$member->memberships()->create([
			'group_id' => 1,
			'activity_id' => 1,
			'nami_id' => 652912,
			'created_at' => '2016-01-16'
		]);
		$this->assertCount(1, $member->memberships);

		NaMiMembership::shouldReceive('all')
			->with(1520)
			->andReturn([(object) [
				'entries_aktivBis' => '',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => 'Administration',
				'entries_aktivVon' => '2016-10-30 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-10-30 19:07:10',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ KassiererIn (20)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 675299,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object) [
				'entries_aktivBis' => '207-01-01',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2016-01-18 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2016-01-18 23:12:12',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => 'Wölfling',
				'entries_taetigkeit' => '€ LeiterIn (6)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 652912,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			], (object)[
				'entries_aktivBis' => '2016-01-18 00:00:00',
				'entries_beitragsArt' => '',
				'entries_caeaGroup' => '',
				'entries_aktivVon' => '2015-01-01 00:00:00',
				'descriptor' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'representedClass' => 'de.iconcept.nami.entity.zuordnung.TaetigkeitAssignment',
				'entries_anlagedatum' => '2015-02-03 16:00:50',
				'entries_caeaGroupForGf' => '',
				'entries_untergliederung' => '',
				'entries_taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz) (40)',
				'entries_gruppierung' => 'Solingen-Wald, Silva 100105',
				'id' => 623406,
				'entries_mitglied' => 'Steinbach, A. Mitglied: 141667'
			]]);

		NaMiMembership::shouldReceive('single')
			->with(675299)
			->andReturn((object)[
				'id' => 675299,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ KassiererIn',
				'taetigkeitId' => 20,
				'caeaGroup' => 'Administration',
				'caeaGroupId' => 324,
				'aktivVon' => '2016-10-30 00:00:00',
				'aktivBis' => '',
			]);

		NaMiMembership::shouldReceive('single')
			->with(652912)
			->andReturn((object)[
				'id' => 652912,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ LeiterIn',
				'taetigkeitId' => 6,
				'untergliederung' => 'Wölfling',
				'untergliederungId' => 1,
				'aktivVon' => '2016-01-18 00:00:00',
				'aktivBis' => '2017-01-01'
			]);

		NaMiMembership::shouldReceive('single')
			->with(623406)
			->andReturn((object)[
				'id' => 623406,
				'gruppierung' => 'Solingen-Wald, Silva 100105',
				'gruppierungId' => 100105,
				'taetigkeit' => '€ sonst. MitarbeiterIn (mit Versicherungsschutz)',
				'taetigkeitId' => 40,
				'aktivVon' => '2015-01-01 00:00:00',
				'aktivBis' => '2016-01-18 00:00:00',
			]);

		NaMiMember::importMemberships($member);

		$this->assertNotNull($member->memberships);
		$memberships = $member->memberships;
		$this->assertCount(1, $memberships);
		$this->assertEquals(1, $memberships->first()->activity->id);
		$this->assertEquals(1, $memberships->first()->group->id);
		$this->assertEquals('2016-01-16', $memberships->first()->created_at->format('Y-m-d'));
	}
}

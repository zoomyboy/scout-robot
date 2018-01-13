<?php

namespace App\Integration\NaMi;

use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMember;
use Tests\Traits\SetsUpNaMi;
use App\Member;

class GetMembersTest extends IntegrationTestCase {
	use DatabaseMigrations;
	use SetsUpNaMi;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_saves_a_members_basic_data() {
		$this->authAsApi();

		NaMiMember::shouldReceive('all')->once()->andReturn(json_decode('[{"id": 245852}]'));
		NaMiMember::shouldReceive('getConfig')->once()->andReturn(\App\Conf::first());
		NaMiMember::shouldReceive('single')
			->with(245852)
			->andReturn(json_decode('{
				"jungpfadfinder": null,
				"mglType": "Mitglied",
				"geschlecht": "männlich",
				"staatsangehoerigkeit": "deutsch",
				"ersteTaetigkeitId": null,
				"ersteUntergliederung": "Jungpfadfinder",
				"emailVertretungsberechtigter": "eltern@test.de",
				"lastUpdated": "2016-01-20 19:45:24",
				"ersteTaetigkeit": null,
				"nameZusatz": "Zusatz",
				"id": 245852,
				"staatsangehoerigkeitId": 1054,
				"version": 30,
				"sonst01": false,
				"sonst02": false,
				"spitzname": "Spitz",
				"landId": 1,
				"staatsangehoerigkeitText": "Andere",
				"gruppierungId": 100105,
				"mglTypeId": "MITGLIED",
				"beitragsart": "Voller Beitrag",
				"nachname": "Mustermann",
				"eintrittsdatum": "2015-05-27 00:00:00",
				"rover": null,
				"region": "Nordrhein-Westfalen (Deutschland)",
				"status": "Aktiv",
				"konfession": "evangelisch / protestantisch",
				"fixBeitrag": null,
				"konfessionId": 2,
				"zeitschriftenversand": true,
				"pfadfinder": null,
				"telefon3": "+49 222 33333",
				"kontoverbindung": {
					"id": 227580,
					"institut": "",
					"bankleitzahl": "",
					"kontonummer": "",
					"iban": "",
					"bic": "",
					"kontoinhaber": "",
					"mitgliedsNummer": 245852,
					"zahlungsKonditionId": null,
					"zahlungsKondition": null
				},
				"geschlechtId": 19,
				"land": "Deutschland",
				"email": "member@test.de",
				"telefon1": "+49 212 319345",
				"woelfling": null,
				"telefon2": "+49 163 1725774",
				"strasse": "Strasse 1",
				"vorname": "Max",
				"mitgliedsNummer": 245852,
				"gruppierung": "Solingen-Wald, Silva 100105",
				"austrittsDatum": "",
				"ort": "City",
				"ersteUntergliederungId": null,
				"wiederverwendenFlag": true,
				"regionId": 10,
				"geburtsDatum": "2005-12-28 00:00:00",
				"stufe": "Jungpfadfinder",
				"genericField1": "",
				"genericField2": "",
				"telefax": "+49 212 4455555",
				"beitragsartId": 1,
				"plz": "42777"
			}'));

		NaMiMember::makePartial();

		$response = $this->postApi('nami/getmembers', []);

		$this->assertCount(1, Member::get());

		$m = Member::first();
		$this->assertEquals('Max', $m->firstname);
		$this->assertEquals('Mustermann', $m->lastname);
		$this->assertEquals('Strasse 1', $m->address);
		$this->assertEquals('42777', $m->zip);
		$this->assertEquals('City', $m->city);
		$this->assertEquals('+49 212 319345', $m->phone);
		$this->assertEquals('+49 163 1725774', $m->mobile);
		$this->assertEquals('+49 212 4455555', $m->fax);
		$this->assertEquals('Spitz', $m->nickname);
		$this->assertEquals('Männlich', $m->gender->title);
		$this->assertEquals('+49 222 33333', $m->business_phone);
		$this->assertEquals('Zusatz', $m->further_address);
		$this->assertEquals('Andere', $m->other_country);
		$this->assertEquals('member@test.de', $m->email);
		$this->assertEquals('eltern@test.de', $m->email_parents);

		$this->assertEquals('Männlich', $m->gender->title);
		$this->assertEquals('E-Mail', $m->way->title);
		$this->assertEquals('Evangelisch / Protestantisch', $m->confession->title);
		$this->assertEquals('Nordrhein-Westfalen', $m->region->title);
		$this->assertEquals('Deutschland', $m->country->title);

		$this->assertEquals('2005-12-28', $m->birthday->format('Y-m-d'));
		$this->assertEquals('2015-05-27', $m->joined_at->format('Y-m-d'));

		$this->assertTrue($m->keepdata);
		$this->assertTrue($m->sendnewspaper);
	}

	/** @test */
	public function it_saves_a_members_basic_data_and_katholisch() {
		$this->authAsApi();

		NaMiMember::shouldReceive('all')->once()->andReturn(json_decode('[{"id": 245852}]'));
		NaMiMember::shouldReceive('getConfig')->once()->andReturn(\App\Conf::first());
		NaMiMember::shouldReceive('single')
			->with(245852)
			->andReturn(json_decode('{
				"jungpfadfinder": null,
				"mglType": "Mitglied",
				"geschlecht": "männlich",
				"staatsangehoerigkeit": "deutsch",
				"ersteTaetigkeitId": null,
				"ersteUntergliederung": "Jungpfadfinder",
				"emailVertretungsberechtigter": "eltern@test.de",
				"lastUpdated": "2016-01-20 19:45:24",
				"ersteTaetigkeit": null,
				"nameZusatz": "Zusatz",
				"id": 245852,
				"staatsangehoerigkeitId": 1054,
				"version": 30,
				"sonst01": false,
				"sonst02": false,
				"spitzname": "Spitz",
				"landId": 1,
				"staatsangehoerigkeitText": "Andere",
				"gruppierungId": 100105,
				"mglTypeId": "MITGLIED",
				"beitragsart": "Voller Beitrag",
				"nachname": "Mustermann",
				"eintrittsdatum": "2015-05-27 00:00:00",
				"rover": null,
				"region": "Nordrhein-Westfalen (Deutschland)",
				"status": "Aktiv",
				"konfession": "römisch-katholisch",
				"fixBeitrag": null,
				"konfessionId": 1,
				"zeitschriftenversand": true,
				"pfadfinder": null,
				"telefon3": "+49 222 33333",
				"kontoverbindung": {
					"id": 227580,
					"institut": "",
					"bankleitzahl": "",
					"kontonummer": "",
					"iban": "",
					"bic": "",
					"kontoinhaber": "",
					"mitgliedsNummer": 245852,
					"zahlungsKonditionId": null,
					"zahlungsKondition": null
				},
				"geschlechtId": 19,
				"land": "Deutschland",
				"email": "member@test.de",
				"telefon1": "+49 212 319345",
				"woelfling": null,
				"telefon2": "+49 163 1725774",
				"strasse": "Strasse 1",
				"vorname": "Max",
				"mitgliedsNummer": 245852,
				"gruppierung": "Solingen-Wald, Silva 100105",
				"austrittsDatum": "",
				"ort": "City",
				"ersteUntergliederungId": null,
				"wiederverwendenFlag": true,
				"regionId": 10,
				"geburtsDatum": "2005-12-28 00:00:00",
				"stufe": "Jungpfadfinder",
				"genericField1": "",
				"genericField2": "",
				"telefax": "+49 212 4455555",
				"beitragsartId": 1,
				"plz": "42777"
			}'));

		NaMiMember::makePartial();

		$response = $this->postApi('nami/getmembers', []);

		$this->assertCount(1, Member::get());

		$m = Member::first();
		$this->assertEquals('Max', $m->firstname);
		$this->assertEquals('Mustermann', $m->lastname);
		$this->assertEquals('Strasse 1', $m->address);
		$this->assertEquals('42777', $m->zip);
		$this->assertEquals('City', $m->city);
		$this->assertEquals('+49 212 319345', $m->phone);
		$this->assertEquals('+49 163 1725774', $m->mobile);
		$this->assertEquals('+49 212 4455555', $m->fax);
		$this->assertEquals('Spitz', $m->nickname);
		$this->assertEquals('Männlich', $m->gender->title);
		$this->assertEquals('+49 222 33333', $m->business_phone);
		$this->assertEquals('Zusatz', $m->further_address);
		$this->assertEquals('Andere', $m->other_country);
		$this->assertEquals('member@test.de', $m->email);
		$this->assertEquals('eltern@test.de', $m->email_parents);

		$this->assertEquals('Männlich', $m->gender->title);
		$this->assertEquals('E-Mail', $m->way->title);
		$this->assertEquals('Römisch-Katholisch', $m->confession->title);
		$this->assertEquals('Nordrhein-Westfalen', $m->region->title);
		$this->assertEquals('Deutschland', $m->country->title);

		$this->assertEquals('2005-12-28', $m->birthday->format('Y-m-d'));
		$this->assertEquals('2015-05-27', $m->joined_at->format('Y-m-d'));

		$this->assertTrue($m->keepdata);
		$this->assertTrue($m->sendnewspaper);
	}
}

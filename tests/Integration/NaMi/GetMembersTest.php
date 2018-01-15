<?php

namespace App\Integration\NaMi;

use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMember;
use Tests\Traits\SetsUpNaMi;
use App\Member;
use \Mockery as M;

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
		$this->runSeeder('NationalitySeeder');

		$this->setUpNaMi();
	}

	public function dataProvider() {
		$values = [];

		$values[] = [
			[],
			[
				'firstname' => 'Max',
				'lastname' => 'Mustermann',
				'address' => 'Strasse 1',
				'zip' => '42777',
				'city' => 'City',
				'phone' => '+49 212 319345',
				'mobile' => '+49 163 1725774',
				'fax' => '+49 212 4455555',
				'nickname' => 'Spitz',
				'business_phone' => '+49 222 33333',
				'further_address' => 'Zusatz',
				'other_country' => 'Andere',
				'email' => 'member@test.de',
				'email_parents' => 'eltern@test.de',
			], [
				'gender' => 'Männlich',
				'way' => 'E-Mail',
				'confession' => 'Evangelisch / Protestantisch',
				'region' => 'Nordrhein-Westfalen',
				'country' => 'Deutschland'
			],
			[
				'birthday' => '2005-12-28',
				'joined_at' => '2015-05-27'
			], [
				'keepdata' => true,
				'sendnewspaper' => true
			]
		];

		foreach(\App\Confession::get() as $c) {
			$values[] = [
				['konfessionId' =>  $c->nami_id], [], ['confession' => $c->title], [], []
			];
		}
		$values[] = [
			['konfessionId' => null], [], ['confession' => null], [], []
		];

		foreach(\App\Gender::get() as $c) {
			if ($c->is_null) {
				$values[] = [
					['geschlechtId' =>  $c->nami_id], [], ['gender' => null], [], []
				];
			} else {
				$values[] = [
					['geschlechtId' =>  $c->nami_id], [], ['gender' => $c->title], [], []
				];
			}
		}

		foreach(\App\Nationality::get() as $n) {
			$values[] = [
				['staatsangehoerigkeitId' =>  $n->nami_id], [], ['nationality' => $n->title], [], []
			];
		}

		foreach(\App\Country::get() as $n) {
			$values[] = [
				['landId' =>  $n->nami_id], [], ['country' => $n->title], [], []
			];
		}

		foreach(\App\Region::get() as $c) {
			if ($c->is_null) {
				$values[] = [
					['regionId' =>  $c->nami_id], [], ['region' => null], [], []
				];
			} else {
				$values[] = [
					['regionId' =>  $c->nami_id], [], ['region' => $c->title], [], []
				];
			}
		}

		return $values;
	}

	/** @test */
	public function it_saves_a_membrs_basic_data() {
		$data = $this->dataProvider();

		$this->authAsApi();

		$ids = range(500, 10000);
		shuffle($ids);
		$ids = array_slice($ids, 0, count($data));
		$ids = array_map(function($id) {
			return (object)['id' => $id];
		}, $ids);

		NaMiMember::shouldReceive('all')->andReturn($ids);
		NaMiMember::shouldReceive('getConfig')->andReturn(\App\Conf::first());

		foreach(array_column($this->dataProvider(), 0) as $i => $mock) {
			NaMiMember::shouldReceive('single')
				->with($ids[$i]->id)
				->andReturn($this->mockDb($mock, $ids[$i]->id));
		}

		NaMiMember::makePartial();

		$response = $this->postApi('nami/getmembers', []);

		foreach($data as $i => $info) {
			 $this->runTestWith($ids[$i]->id, ...$info);
		}
		$this->assertCount(count($data), Member::get());
	}

	private function runTestWith($id, $mock, $values, $relations, $dates, $booleans) {
		$m = Member::where('nami_id', $id)->first();
		foreach($values as $key => $v) {
			$this->assertEquals($v, $m->{$key}, 'Failed asserting that Property '.$key.' has the Value '.$v.'.'."\n Mock: ".print_r($mock, true)."\n Values: ".print_r($values, true));
		}

		foreach($relations as $key => $r) {
			if (is_null($r)) {
				$this->assertNull($m->{$key}, 'Failed asserting that relation '.$m->{$key}.' is null. Factory data: '.print_r($this->mockDb($mock, $id), true)."\n\n Expected Data: ".print_r($relations, true));
			} else {
				$this->assertNotNull($m->{$key}, 'Failed asserting that '.$m.' has a valid '.$key."\n Factory: ".print_r(NaMiMember::single($id), true)."\n\n");
				$this->assertEquals($r, $m->{$key}->title, 'Failed asserting that relation '.$m->{$key}->title.' is expected '.$r.'. Factory data: '.print_r($mock, true)."\n\n Expected Data: ".print_r($relations, true));
			}
		}

		foreach($dates as $key => $d) {
			$this->assertEquals($d, $m->{$key}->format('Y-m-d'));
		}

		foreach($booleans as $key => $b) {
			$this->assertEquals($b, $m->{$key});
		}

		M::close();
	}

	private function mockDb($attr = [], $id = null) {
		return (object) array_merge([
			'jungpfadfinder' => null,
			'mglType' => 'Mitglied',
			'geschlecht' => 'männlich',
			'staatsangehoerigkeit' => 'deutsch',
			'ersteTaetigkeitId' => null,
			'ersteUntergliederung' => 'Jungpfadfinder',
			'emailVertretungsberechtigter' => 'eltern@test.de',
			'lastUpdated' => '2016-01-20 19:45:24',
			'ersteTaetigkeit' => null,
			'nameZusatz' => 'Zusatz',
			'id' => $id ?? 20000001,
			'staatsangehoerigkeitId' => 1054,
			'version' => 30,
			'sonst01' => false,
			'sonst02' => false,
			'spitzname' => 'Spitz',
			'landId' => 1,
			'staatsangehoerigkeitText' => 'Andere',
			'gruppierungId' => 100105,
			'mglTypeId' => 'MITGLIED',
			'beitragsart' => 'Voller Beitrag',
			'nachname' => 'Mustermann',
			'eintrittsdatum' =>'2015-05-27 00:00:00',
			'rover' => null,
			'region' => 'Nordrhein-Westfalen (Deutschland)',
			'status' => 'Aktiv',
			'konfession' => 'evangelisch / protestantisch',
			'fixBeitrag' => null,
			'konfessionId' => 2,
			'zeitschriftenversand' => true,
			'pfadfinder' => null,
			'telefon3' => '+49 222 33333',
			'kontoverbindung' => (object)[
				'id' => 227580,
				'institut' => '',
				'bankleitzahl' => '',
				'kontonummer' => '',
				'iban' => '',
				'bic' => '',
				'kontoinhaber' => '',
				'mitgliedsNummer' => 245852,
				'zahlungsKonditionId' => null,
				'zahlungsKondition' => null
			],
			"geschlechtId" => 19,
			'land' => 'Deutschland',
			'email' => 'member@test.de',
			'telefon1' => '+49 212 319345',
			'woelfling' => null,
			'telefon2' => '+49 163 1725774',
			'strasse' => 'Strasse 1',
			'vorname' => 'Max',
			'mitgliedsNummer' => 245852,
			'gruppierung' => 'Solingen-Wald, Silva 100105',
			'austrittsDatum' => '',
			'ort' => 'City',
			'ersteUntergliederungId' => null,
			'wiederverwendenFlag' => true,
			'regionId' => 10,
			'geburtsDatum' => '2005-12-28 00:00:00',
			'stufe' => 'Jungpfadfinder',
			'genericField1' => '',
			'genericField2' => '',
			'telefax' => '+49 212 4455555',
			'beitragsartId' => 1,
			'plz' => '42777'
		], $attr);
	}
}

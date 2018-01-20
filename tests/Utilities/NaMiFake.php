<?php

namespace Tests\Utilities;

use App\Facades\NaMi\NaMiMember;

class NaMiFake {

	public $members;

	public function getConfig() {
		return \App\Conf::first();
	}

	public function createMember($data) {
		$this->members[] = (object) array_merge($this->fakeValues(), $data);
	}

	public function updateMember($id, $data) {
		$this->members = array_map(function($m) use ($id, $data) {
			if ($m->id == $id) {
				return (object) array_merge((array)$m, $data);
			}

			return $m;
		}, $this->members);
	}

	public function get($url) {
		if (preg_match('#^/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/[0-9]+/flist$#', $url)) {
			$group = str_replace('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/', '', $url);
			$group = str_replace('/flist', '', $group);

			if ($group != $this->getConfig()->namiGroup) {
				return (object) [
					"success" => false,
					"responseType" => "EXCEPTION",
					"message" => "Sicherheitsverletzung: Zugriff auf Rechte Recht (n:2001002 o:2) fehlgeschlagen"
				];
			}

			return $this->successResponse($this->members);
		}

		if ($url == '/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root') {
			return $this->successResponse([(object) ["id" => $this->getConfig()->namiGroup]]);
        }

		preg_match_all('#^/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/([0-9]+)/([0-9]+)$#', $url, $matches);
		if (isset($matches[1][0]) && isset($matches[2][0]) && is_numeric($matches[1][0]) && is_numeric($matches[2][0])) {
			$memberId = $matches[2][0];

			$validMembers = array_values(array_filter($this->members, function($m) use ($memberId) {
				return $m->id == $memberId;
			}));

			if (!count($validMembers)) {
				return (object) [
    				"success" => false,
    				"data" => null,
    				"responseType" => "EXCEPTION",
    				"message" => "Access denied - no right for requested operation",
    				"title" => "Exception"
				];
			}

    		return (object) ["success" => true, "data" => $validMembers[0], "responseType" => null, "message" => null, "title" => null];
		}

		preg_match_all('#^/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/([0-9]+)/flist$#', $url, $matches);
		if (isset($matches[1][0]) && is_numeric($matches[1][0])) {
			$memberId = $matches[1][0];

			return (object) ['success' => true, 'responseType' => "OK", 'data' => $this->memberships[$memberId] ?? []];
		}
	}

	public function successResponse($data) {
		return (object) [
			"success" => true,
			"data" => $data,
			"responseType" => "OK"
		];
	}

	public function mockDb($attrs = [], $ids = null) {
		$config = \App\Conf::first();

		$ids = array_map(function($id) {
			return (object)['id' => $id];
		}, $ids);

		NaMiMembership::shouldReceive('all')->andReturn([]);

		NaMi::shouldReceive('get')
			->with('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$config->namiGroup.'/flist')
			->andReturn((object)['success' => true, 'responseType' => 'OK', 'data' => $ids]);

		foreach($ids as $key => $id) {
			NaMi::shouldReceive('get')
				->with('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$config->namiGroup.'/'.$id->id)
				->andReturn((object) ['success' => true, 'data' => (object) array_merge([], $attrs[$key])]);
		}
	}

	public function fakeValues() {
		return [
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
			'id' => 5678,
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
		];
	}
}

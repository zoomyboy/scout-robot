<?php

namespace App\Services\NaMi;

use GuzzleHttp\Client;
use App\Exceptions\NaMi\GroupException;
use App\Exceptions\NaMi\SystemException;
use App\Facades\NaMi\NaMiGroup;

class Member extends NaMiService {
	public function __construct() {
		parent::__construct();
	}

	public function single($memberId) {
		$group = $this->getConfig()->namiGroup;
		$url = '/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$group.'/'.$memberId;
		$response = $this->get($url);

		if ($response->success === true) {
			return $response->data;
		}

		if (! NaMiGroup::hasAccess($group)) {
			throw new GroupException('Du hast keinen Zugriff auf diese Gruppierung');
		}

		throw (new SystemException('Unknown Error'))
			->setUrl($url)
			->setResponse($response);
	}

	public function all() {
		$group = $this->config->namiGroup;
		$url = '/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$group.'/flist';
		$response = $this->get($url);

		if ($this->isSuccess($response)) {
			return $response->data;
		}

		if (! NaMiGroup::hasAccess($group)) {
			throw new GroupException('Du hast keinen Zugriff auf diese Gruppierung');
		}

		throw (new SystemException('Unknown Error'))
			->setUrl($url)
			->setResponse($response);
	}

	public function importMember($data) {
		$gender = \App\Gender::get()->first(function($g) use ($data) {
			return strtolower($g->title) == $data->geschlecht;
		});

		$confession = \App\Confession::where('nami_id', $data->konfessionId)->first();
		$region = \App\Region::where('nami_id', $data->regionId)->where('is_null', false)->first();

		$country = \App\Country::where('nami_id', $data->landId)->first();

		$m = new \App\Member([
			'firstname' => $data->vorname,
			'lastname' => $data->nachname,
			'nickname' => $data->spitzname,
			'joined_at' => $data->eintrittsdatum,
			'birthday' => $data->geburtsDatum,
			'keepdata' => $data->wiederverwendenFlag,
			'sendnewspaper' => $data->zeitschriftenversand,
			'address' => $data->strasse,
			'zip' => $data->plz,
			'city' => $data->ort,
			'nickname' => $data->spitzname,
			'other_country' => $data->staatsangehoerigkeitText,
			'further_address' => $data->nameZusatz,
			'phone' => $data->telefon1,
			'mobile' => $data->telefon2,
			'business_phone' => $data->telefon3,
			'fax' => $data->telefax,
			'email' => $data->email,
			'email_parents' => $data->emailVertretungsberechtigter,
			'nami_id' => $data->id
		]);

		$m->gender()->associate($gender);
		$m->country()->associate($country);
		$m->region()->associate($region);
		$m->way()->associate($this->getConfig()->defaultWay->id);
		$m->confession()->associate($confession);

		$m->save();
	}
}

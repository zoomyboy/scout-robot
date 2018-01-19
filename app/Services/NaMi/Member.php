<?php

namespace App\Services\NaMi;

use GuzzleHttp\Client;
use App\Exceptions\NaMi\GroupException;
use App\Exceptions\NaMi\SystemException;
use App\Facades\NaMi\NaMiGroup;
use App\Member as MemberModel;
use App\Facades\NaMi\NaMiMembership;
use App\Activity;
use App\Group;

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
		$gender = \App\Gender::where('nami_id', $data->geschlechtId)->where('is_null', false)->first();

		$confession = $data->konfessionId
			? \App\Confession::where('nami_id', $data->konfessionId)->first()
			: null
		;
		$region = \App\Region::where('nami_id', $data->regionId)->where('is_null', false)->first();

		$country = \App\Country::where('nami_id', $data->landId)->first();
		$nationality = \App\Nationality::where('nami_id', $data->staatsangehoerigkeitId)->first();

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
		$m->nationality()->associate($nationality);

		$m->save();

		return $m;
	}

	public function store(MemberModel $member) {
		$group = $this->getConfig()->namiGroup;

		$gender = $member->gender
			? $member->gender->nami_id 
			: \App\Gender::where('is_null', true)->first()->nami_id
		;

		$region = $member->region
			? $member->region->nami_id 
			: \App\Region::where('is_null', true)->first()->nami_id
		;

		$nationality = $member->nationality->nami_id;
		$confession = $member->confession
			? $member->confession->nami_id
			: null;

		$response = $this->post('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$group, [
			'vorname' => $member->firstname,
			'nachname' => $member->lastname,
			'eintrittsdatum' => $member->joined_at->format('Y-m-d').'T00:00:00',
			'geburtsDatum' => $member->birthday->format('Y-m-d').'T00:00:00',
			'geschlechtId' => $gender,
			'staatsangehoerigkeitId' => $nationality,
			'strasse' => $member->address,
			'plz' => $member->zip,
			'regionId' => $region,
			'ort' => $member->city,
			'landId' => $member->country->nami_id,
			'ersteTaetigkeitId' => 35,
			'spitzname' => $member->nickname,
			'staatsangehoerigkeitText' => $member->other_country,
			'konfessionId' => $confession,
			'konfessionId' => $confession,
			'wiederverwendenFlag' => $member->keepdata,
			'zeitschriftenversand' => $member->sendnewspaper,
			'nameZusatz' => $member->further_address,
			'telefon1' => $member->phone,
			'telefon2' => $member->mobile,
			'telefon3' => $member->business_phone,
			'telefax' => $member->fax,
			'email' => $member->email,
			'emailVertretungsberechtigter' => $member->email_parents,
		]);

		return is_numeric($response->data) && $response->success === true
			? $response->data
			: false;
	}

	/**
	 * Gets the memberships from nami for the given Member and synchs it
	 *
	 * @param MemberModel $member The Member Model
	 */
	public function importMemberships(MemberModel $member) {
		$memberships = NaMiMembership::all($member->nami_id);
		$memberships = collect($memberships)->map(function($ms) use ($member) {
			return NaMiMembership::single($member->nami_id, $ms->id);
		});

		$memberships = $memberships->filter(function($ms) {
			return $ms->aktivBis == ''
				&& isset($ms->taetigkeitId) && Activity::where('nami_id', $ms->taetigkeitId)->first() != null
				&& isset($ms->untergliederungId) && Group::where('nami_id', $ms->untergliederungId)->first() != null;
		});

		foreach($memberships as $ms) {
			$member->memberships()->create([
				'activity_id' => Activity::where('nami_id', $ms->taetigkeitId)->first()->id,
				'nami_id' => $ms->id,
				'group_id' => Group::where('nami_id', $ms->untergliederungId)->first()->id,
				'created_at' => $ms->aktivVon
			]);

		}
	}
}

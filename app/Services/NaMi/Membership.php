<?php

namespace App\Services\NaMi;

use GuzzleHttp\Client;
use App\Exceptions\NaMi\GroupException;
use App\Exceptions\NaMi\SystemException;
use App\Facades\NaMi\NaMiGroup;
use App\Membership as MembershipModel;
use App\Facades\NaMi\NaMi;
use Carbon\Carbon;

class Membership extends NaMiService {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Gets a single membership from nami
	 *
	 * @param int $memberId The real Nami member id
	 * @param int $membershipId The real nami membership id
	 */
	public function single($memberId, $membershipId) {
		$url = '/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/'.$memberId.'/'.$membershipId;
		$response = NaMi::get($url);

		if ($response->success === true) {
			return $response->data;
		}
	}

	/**
	 * Get all memberships from nami for one member
	 *
	 * @param int $memberId The ID of the Member inside NaMi
	 */
	public function all($memberId) {
		$url = '/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/'.$memberId.'/flist';
		$response = NaMi::get($url);

		if ($this->isSuccess($response)) {
			return $response->data;
		}
	}

	public function store(MembershipModel $membership) {
		$group = NaMi::getConfig()->namiGroup;

		$response = NaMi::post('/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/'.$membership->member->id, [
			'gruppierungId' => $group,
			'aktivVon' => $membership->created_at->format('Y-m-d').'T00:00:00',
			'aktivBis' => null,
			'taetigkeitId' => $membership->activity->nami_id,
			'untergliederungId' => $membership->group->nami_id,
			'beitragsArtId' => null,
			'caeaGroupId' => null,
			'caeaGroupForGfId' => null
		]);

		return is_numeric($response->data) && $response->success === true
			? $response->data
			: false;
	}
}

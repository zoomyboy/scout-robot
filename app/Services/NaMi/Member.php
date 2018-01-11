<?php

namespace App\Services\NaMi;

use GuzzleHttp\Client;
use App\Exceptions\NaMi\GroupAccessDeniedException;

class Member extends NaMiService {
	public function __construct() {
		parent::__construct();
	}

	public function all($group) {
		$response = $this->get('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/'.$group.'/flist');

		if (isset ($response->success) && $response->success === false) {
			if (str_contains($response->message, 'Sicherheitsverletzung')) {
				throw new GroupAccessDeniedException('Du hast keinen Zugriff auf diese Gruppierung');
			}
		}

		return $response;
	}

	public function checkCredentials($user, $password, $group) {
		$this->setPassword($password);
		$this->setUser($user);
		$this->all($group);
	}
}

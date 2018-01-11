<?php

namespace App\Services\NaMi;

use GuzzleHttp\Client;

class Member extends NaMiService {
	public function __construct() {
		parent::__construct();
	}

	public function all() {
		return $this->get('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/100105/flist');
	}
}

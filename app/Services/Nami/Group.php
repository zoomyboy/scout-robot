<?php

namespace App\Services\NaMi;

use App\Exceptions\NaMi\GroupException;
use App\Facades\NaMi\NaMi;
use App\NaMi\Interfaces\UserResolver;
use App\Nami\Service;
use GuzzleHttp\Client as GuzzleClient;

class Group {
    public function __construct() {
    }

	public function all() {
        $r = $nami->get('/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root');

		if ($this->isSuccess($response)) {
			return $response->data;
		}

		throw new GroupException('Verfügbare Gruppen konnten nicht abgerufen werden');
	}

	public function hasAccess($group) {
		return collect($this->all())
			->where('id', $group)
			->count() == 1;

	}
}

<?php

namespace App\Services\NaMi;

use App\Exceptions\NaMi\GroupException;
use App\Facades\NaMi\NaMi;
use App\NaMi\Interfaces\UserResolver;
use GuzzleHttp\Client as GuzzleClient;

class Group extends NaMiService {
    public function __construct(GuzzleClient $client, UserResolver $user) {
        parent::__construct($client, $user);
    }

	public function all() {
		$response = NaMi::get('/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root');

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

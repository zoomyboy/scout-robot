<?php

namespace App\Services\NaMi;

use App\Http\Exceptions\NaMi\GroupException;

class Group extends NaMiService {
	public function __construct() {
		parent::__construct();
	}

	public function all() {
		$response = $this->get('/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root');

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

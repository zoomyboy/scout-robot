<?php

namespace App\Nami\Receiver;

use App\Nami\Interfaces\UserResolver;
use App\Nami\Service;

class Membership {

    private $service;

    public function __construct(Service $service) {
        $this->service = $service;
    }

    public function all($memberId) {
        $members = $this->service->get("/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/{$memberId}");

        return collect($members->get('data'));
    }

    public function single($memberId, $membershipId) {
        $members = $this->service->get("/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/{$memberId}/{$membershipId}");

        return $members->get('data');
    }
}

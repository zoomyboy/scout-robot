<?php

namespace App\Nami\Receiver;

use App\NaMi\Interfaces\UserResolver;
use App\Nami\Service;

class Member {

    private $service;
    private $userResolver;

    public function __construct(Service $service, UserResolver $userResolver) {
        $this->service = $service;
        $this->userResolver = $userResolver;
    }

    public function all() {
        $members = $this->service->get("/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/{$this->userResolver->getGroup()}/flist");

        return collect($members->get('data'));
    }

    public function single($memberId) {
        $member = $this->service->get("/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/{$this->userResolver->getGroup()}/$memberId");

        return $member->get('data');
    }
}

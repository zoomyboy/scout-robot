<?php

namespace App\Nami\Receiver;

use App\NaMi\Interfaces\UserResolver;
use App\Nami\Service;

class Group {

    private $service;
    private $userResolver;

    public function __construct(Service $service, UserResolver $userResolver) {
        $this->service = $service;
        $this->userResolver = $userResolver;
    }

    public function all() {
        $members = $this->service->get('/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root');

        return collect($members->get('data'))->map(function($group) {
            $group->title = $group->descriptor;
            unset($group->descriptor);

            return $group;
        });
    }
}

<?php

namespace App\Nami\Manager;

use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Service;
use App\Member as MemberModel;

class Member {
    private $memberReceiver;
    private $membershipManager;

    public function __construct(MemberReceiver $memberReceiver, MembershipManager $membershipManager) {
        $this->memberReceiver = $memberReceiver;
        $this->membershipManager = $membershipManager;
    }

    public function pull($memberId) {
        $data = $this->memberReceiver->single($memberId);

        $gender = \App\Gender::where('nami_id', $data->geschlechtId)->where('is_null', false)->first();

        $confession = $data->konfessionId
            ? \App\Confession::where('nami_id', $data->konfessionId)->first()
            : null
        ;
        $region = \App\Region::where('nami_id', $data->regionId)->where('is_null', false)->first();

        $country = \App\Country::where('nami_id', $data->landId)->first();
        $nationality = \App\Nationality::where('nami_id', $data->staatsangehoerigkeitId)->first();

        $sub = null;
        $fee = \App\Fee::where('nami_id', $data->beitragsartId)->first();
        if (!is_null($fee) && !is_null($fee->subscriptions->first())) {
            $sub = $fee->subscriptions->first();
        }

        $attributes = [
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
            'nami_id' => $data->id,
            'active' => $data->status == 'Aktiv'
        ];

        if (MemberModel::nami($data->id)->exists()) {
            $m = MemberModel::nami($data->id)->first();
            $m->update($attributes);
        } else {
            $m = new MemberModel($attributes);
        }

        $m->gender()->associate($gender);
        $m->country()->associate($country);
        $m->region()->associate($region);
        $m->way()->associate(\Setting::get('defaultWay'));
        $m->confession()->associate($confession);
        $m->nationality()->associate($nationality);
        $m->subscription()->associate($sub);

        $m->save();

        $this->membershipManager->pull($memberId);

        return $m;
    }

    public function push(MemberModel $member) {
        // @todo Update member with push to nami member receiver
    }
}

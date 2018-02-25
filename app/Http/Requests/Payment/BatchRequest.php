<?php

namespace App\Http\Requests\Payment;

use Zoomyboy\BaseRequest\Request;
use App\Member;
use App\Payment;
use App\Status;

class BatchRequest extends Request {
    public function rules() {
        return [
            'nr' => 'required'
        ];
    }

    public function persist($model = null) {
        foreach(Member::active()->get() as $member) {
            if ($member->payments()->where('nr', $this->nr)->get()->first() != null
                || is_null($member->subscription)
            ) {
                continue;
            }

            $payment = new Payment(['nr' => $this->nr]);
            $payment->status()->associate(Status::find(1));
            $payment->member()->associate($member);
            $payment->subscription()->associate($member->subscription);
            $payment->save();
        }
    }
}

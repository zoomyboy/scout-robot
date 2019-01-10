<?php

namespace App\Http\Requests\Payment;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;
use App\Status;
use App\Subscription;
use App\Payment;

class StoreRequest extends Request {
    public $model = \App\Payment::class;

    public function rules() {
        return [
            'nr' => ['required', 'numeric', Rule::unique('payments')->where(function($query) {
                return $query->where('member_id', $this->route()->member->id);
            })],
            'status_id' => 'required|exists:statuses,id',
            'subscription_id' => 'required|exists:subscriptions,id',
        ];
    }

    public function messages() {
        return [
            'nr.unique' =>  'Dieses Jahr existiert bereits'
        ];
    }

    public function persist($model = null) {
        $payment = $this->route()->member->createPayment(
            $this->only(['status_id', 'subscription_id', 'nr'])
        );

        return [
            'strikes' => $this->route()->member->strikes,
            'payment' => $payment
        ];
    }
}

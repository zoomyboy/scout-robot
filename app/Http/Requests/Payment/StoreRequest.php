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
			'status' => 'required|exists:statuses,id',
			'subscription' => 'required|exists:subscriptions,id',
		];
	}

	public function messages() {
		return [
			'nr.unique' =>  'Dieses Jahr existiert bereits'
		];
	}

    public function persist($model = null) {
        $s = new Payment(['nr' => $this->nr]);

        $s->member()->associate($this->route()->member);
        $s->status()->associate(Status::find($this->status));
        $s->subscription()->associate(Subscription::find($this->subscription));
        $s->save();
    }
}

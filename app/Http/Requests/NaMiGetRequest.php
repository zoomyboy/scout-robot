<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;
use App\Facades\NaMi\NaMiMember;
use App\Jobs\SyncAllNaMiMembers;
use \App\Member;

class NaMiGetRequest extends Request
{
	public $model = Member::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

	public function customRules() {
		if (!\App\Conf::first()->namiEnabled) {
			return ['error' => 'NaMi ist nicht eingeschaltet. Es kann keine Synchronisation stattfinden.'];
		}

		return [];
	}

	public function persist($model = null) {
		SyncAllNaMiMembers::dispatch();
	}
}

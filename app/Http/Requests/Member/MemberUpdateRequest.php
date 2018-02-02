<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Member;
use App\Group;
use Illuminate\Validation\Rule;
use App\Jobs\UpdateNaMiMember;

class MemberUpdateRequest extends Request
{

	public $model = Member::class;
    public $oldmodel;

	public function rules() {
		$ret = [
			'firstname' => 'required',
			'lastname' => 'required',
			'birthday' => 'required|date',
			'joined_at' => 'required|date',
			'address' => 'required',
			'zip' => 'required|numeric',
			'city' => 'required',
			'country' => 'required|exists:countries,id',
			'way' => 'required|exists:ways,id',
			'nationality' => 'required|exists:nationalities,id',
			'activity' => 'required|exists:activities,id'
		];

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}

		if (is_null(\App\Activity::where('id', $this->activity)->first())) {
			return $ret;
		}

		if (\App\Activity::where('id', $this->activity)->first()->is_payable) {
			$ret['subscription'] = 'required';
		}

		$ret['group'] = [
			'required',
			Rule::in(Group::whereHas('activities', function($q) {
				return $q->where('id', $this->activity);
			})->get()->pluck('id')->toArray())
		];

		return $ret;
	}

    public function persist($model = null) {
        $this->oldmodel = $model->getAttributes();
        return parent::persist($model);
    }

    public function afterPersist($model = null) {
 		if (is_null($model->nami_id)) {
			return;
		}

		if (!\App\Conf::first()->namiEnabled) {
			return;
		}

		UpdateNaMiMember::dispatch($model, $this->oldmodel);
    }
}

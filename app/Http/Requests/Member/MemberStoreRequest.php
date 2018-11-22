<?php

namespace App\Http\Requests\Member;

use App\Group;
use App\Jobs\StoreNaMiMember;
use App\Member;
use App\Rules\FilledOr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Zoomyboy\BaseRequest\Request;

class MemberStoreRequest extends Request
{

	public $model = Member::class;

	public function rules() {
		$ret = [
			'firstname' => 'required',
			'lastname' => 'required',
			'birthday' => 'required|date',
			'joined_at' => 'required|date',
			'address' => 'required',
			'zip' => 'required|numeric',
			'city' => 'required',
			'country_id' => 'required|exists:countries,id',
			'way_id' => 'required|exists:ways,id',
			'nationality_id' => 'required|exists:nationalities,id',
			'activity_id' => 'required|exists:activities,id',
		];

        if ($this->way == 1) {
            $ret['email'] = [new FilledOr($this->email_parents)];
            $ret['email_parents'] = [new FilledOr($this->email)];
        }
        return $ret;

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

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}


		return $ret;
	}

    public function persist($model = null) {
        $model = Member::create($this->except(['activity_id']));

        $model->memberships()->create([
            'activity_id' => $this->activity_id,
            'group_id' => $this->group_id
        ]);

        if (!is_null($model->nami_id)) {
            return;
        }

        if (!\App\Conf::first()->namiEnabled) {
            return;
        }

        StoreNaMiMember::dispatch($model);
    }
}

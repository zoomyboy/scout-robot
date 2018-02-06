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
		];

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}

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

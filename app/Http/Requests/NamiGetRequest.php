<?php

namespace App\Http\Requests;

use App\Jobs\SyncAllNamiMembers;
use Illuminate\Validation\Rule;
use Zoomyboy\BaseRequest\Request;
use \App\Member;

class NamiGetRequest extends Request
{
	public $model = Member::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'active' => 'required',
            'inactive' => 'required'
        ];
    }

	public function customRules() {
		if (!\Setting::get('namiEnabled')) {
			return ['error' => 'NaMi ist nicht eingeschaltet. Es kann keine Synchronisation stattfinden.'];
		}

		return [];
	}

	public function persist($model = null) {
        if (!$this->active && !$this->inactive) {$filter = [];}

        if (!$this->active && $this->inactive) {
            $filter = ['status' => ['Inaktiv']];
        }

        if ($this->active && !$this->inactive) {
            $filter = ['status' => ['Aktiv']];
        }

        if ($this->active && $this->inactive) {
            $filter = ['status' => ['Aktiv', 'Inaktiv']];
        }

        if (!$this->active && !$this->inactive) {
            $filter = ['status' => []];
        }

		SyncAllNamiMembers::dispatch($filter);
	}
}

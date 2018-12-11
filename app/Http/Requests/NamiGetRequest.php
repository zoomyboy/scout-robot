<?php

namespace App\Http\Requests;

use \App\Member;
use App\Jobs\SyncNamiMember;
use Illuminate\Validation\Rule;
use Zoomyboy\BaseRequest\Request;
use App\Nami\Receiver\Member as MemberReceiver;

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
        if (!$this->active && !$this->inactive) {return;}

        if (!$this->active && $this->inactive) {
            $status = ['Inaktiv'];
        }

        if ($this->active && !$this->inactive) {
            $status = ['Aktiv'];
        }

        if ($this->active && $this->inactive) {
            $status = ['Aktiv', 'Inaktiv'];
        }

        $allMembers = app(MemberReceiver::class)->all()
        ->filter(function($member) use ($status) {
            return in_array($member->entries_status, $status);
        })->values();

        foreach($allMembers as $i => $member) {
            SyncNamiMember::dispatch($member->id, ($i+1) / count($allMembers) * 100);
        }
	}
}

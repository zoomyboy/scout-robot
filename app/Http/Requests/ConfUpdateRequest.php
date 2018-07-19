<?php

namespace App\Http\Requests;

use App\Exceptions\NaMi\GroupAccessDeniedException;
use App\Exceptions\NaMi\LoginException;
use App\Exceptions\NaMi\TooManyLoginAttemptsException;
use App\Facades\NaMi\NaMiGroup;
use App\Nami\Rules\ValidNamiCredentials;
use Zoomyboy\BaseRequest\Request;
use Zoomyboy\Fileupload\Image;

class ConfUpdateRequest extends Request
{
	public $model = \App\Conf::class;

	public function authorize() {
		return auth()->guard('api')->user()->can('update', $this->route('conf'));
	}

    public function isNamiEnabled() {
        return \Setting::get('namiEnabled');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->namiEnabled) {
            $rules['namiUser'] = ['required'];
            $rules['namiGroup'] = ['numeric'];
        }

        if($this->namiEnabled && !$this->isNamiEnabled()) {
            $rules['namiPassword'] = ['required'];
        }

        if ($this->namiEnabled && $this->filled(['namiUser', 'namiPassword', 'namiGroup'])) {
            $rules['namiUser'] = [
                'required', app(ValidNamiCredentials::class)
            ];
        }

        return $rules;
    }

    public function modifyFillables($fill) {
        if (!array_key_exists('namiEnabled', $fill) || $fill['namiEnabled'] === false) {
            $fill['namiGroup'] = null;
            $fill['namiUser'] = null;
            $fill['namiPassword'] = null;
        }

        if ($this->isNamiEnabled() && !$this->namiPassword) {
            $fill = array_except($fill, ['namiPassword', 'namiGroup', 'namiUser']);
        }

        return $fill;
    }

	/**
	 * Check nami access
	 */
	public function customRules() {
        return [];
		try {
			$response = app('nami')->checkCredentials($this->namiUser, $this->namiPassword);
		} catch(LoginException $e) {
			return ['namiUser' =>  'Login zu NaMi fehlgeschlagen. Bitte prüfe deine Zugangsdaten.'];
		} catch(TooManyLoginAttemptsException $e) {
			return ['namiUser' =>  'Zu viele Loginversuche. Bitte warte '.$e->time.' Minuten'];
		}

		if (! NaMiGroup::hasAccess($this->namiGroup)) {
			return ['namiGroup' =>  'Du hast keinen Zugriff auf diese Gruppierung'];
		}

		return [];
	}

	public function afterPersist($model = null) {
		foreach($this->input('files') as $file) {
			$image = Image::find($file['id']);
			$image->model()->associate($model);
			$image->save();
		}
	}
}

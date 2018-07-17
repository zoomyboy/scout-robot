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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->namiEnabled && $this->namiGroup) {
            $rules['namiGroup'] = ['numeric', 'max:8'];
        }

        if ($this->namiEnabled && $this->has(['namiUser', 'namiPassword', 'namiGroup'])) {
            $rules['namiUser'] = [
                'required', new ValidNamiCredentials($this->namiUser, $this->namiPassword, $this->namiGroup)
            ];
        }

        return $rules;
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

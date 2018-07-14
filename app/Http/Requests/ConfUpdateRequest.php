<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Zoomyboy\Fileupload\Image;
use App\Exceptions\NaMi\LoginException;
use App\Exceptions\NaMi\TooManyLoginAttemptsException;
use App\Exceptions\NaMi\GroupAccessDeniedException;
use App\Facades\NaMi\NaMiGroup;

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
		return [];
    }

	/**
	 * Check nami access
	 */
	public function customRules() {
		if (!$this->namiEnabled) {
			return [];
		} else {
			if (!$this->namiUser) {
				return ['namiUser' => 'Dieses Feld ist erforderlich'];
			}

			if (!$this->namiGroup) {
				return ['namiGroup' => 'Dieses Feld ist erforderlich'];
			}
		}


		if (!is_numeric($this->namiGroup)) {
			return ['namiGroup' => 'Das hier sollte eine 6-stellige Zahl sein.'];
		}

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

<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UsergroupDeleteRequest extends Request
{
	public $model = \App\Usergroup::class;

	public function customRules() {
		if($this->route()->parameter('usergroup')->users->count()) {
			return ['id' => 'Diese Benutzergruppe hat noch einige Mitglieder. Du kannst sie daher erst löschen, wenn alle Benutzer aus der Gruppe entfernt wurden.'];
		}
	}

	public function persist($model=null) {
		$model->delete();
	}

	public function rules() {
		return [];
	}
}

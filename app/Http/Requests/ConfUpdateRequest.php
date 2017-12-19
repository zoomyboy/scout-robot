<?php

namespace App\Http\Requests;

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

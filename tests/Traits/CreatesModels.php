<?php

namespace Tests\Traits;

trait CreatesModels {
	public function create($model, $params = []) {
		$modelClass = 'App\\'.ucfirst($model);
		return factory($modelClass)->create($params);
	}
}

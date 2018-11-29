<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasTitle;

	public $timestamps = false;

    public $fillable = ['title', 'nami_id'];

	public $casts = [
		'nami_id' => 'integer'
	];

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
}

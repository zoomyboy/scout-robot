<?php

use Illuminate\Database\Seeder;
use \App\Right;

class UsergroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usergroup = \App\Usergroup::create(['title' => 'Super-Administrator']);
		$usergroup->rights()->sync(Right::get()->pluck('id')->toArray());
    }

	public static function default() {
		return \App\Usergroup::where('title', 'Super-Administrator')->first();
	}
}

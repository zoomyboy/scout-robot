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
        $usergroup = \App\Usergroup::create(['title' => config('seed.default_usergroup')]);
		$usergroup->rights()->sync(Right::get()->pluck('id')->toArray());
    }
}

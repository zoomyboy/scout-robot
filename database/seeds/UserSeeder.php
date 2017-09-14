<?php

use Illuminate\Database\Seeder;
use \App\User;
use App\Usergroup;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$user = factory(User::class)->make([
			'name' => config('seed.default_username'),
			'password' => bcrypt(config('seed.default_userpw')),
			'email' => config('seed.default_usermail')
		]);
		$user->usergroup()->associate(Usergroup::where('title', config('seed.default_usergroup'))->first());
		$user->save();
    }
}

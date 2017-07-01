<?php

use Illuminate\Database\Seeder;
use \App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$user = factory(User::class)->make(['name' => 'Administrator', 'password' => 'admin', 'email' => 'admin@example.com']);
		$user->usergroup()->associate(\UsergroupSeeder::default());
		$user->save();
    }
}

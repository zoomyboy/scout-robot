<?php

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		foreach(\App\Member::get() as $member) {
			$years = range(date('Y')-5, date('Y'));
			shuffle($years);

			foreach(array_fill(0, rand(0,5), 0) as $ind) {
				$member->payments()->save(factory(\App\Payment::class)->make(['nr' => array_shift($years)]));
			} 
		}
    }
}

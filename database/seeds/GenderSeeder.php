<?php

use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		\App\Gender::create(['title' => 'Männlich']);
		\App\Gender::create(['title' => 'Weiblich']);
    }
}

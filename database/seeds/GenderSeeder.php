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
		\App\Gender::create(['title' => 'Männlich', 'nami_id' => 19, 'nami_title' => 'männlich', 'is_null' => false]);
		\App\Gender::create(['title' => 'Weiblich', 'nami_id' => 20, 'nami_title' => 'weiblich', 'is_null' => false]);
		\App\Gender::create(['title' => 'keine Angabe', 'nami_id' => 23, 'nami_title' => 'keine Angabe', 'is_null' => true]);
    }
}

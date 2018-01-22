<?php

use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Fee::create(['title' => 'Voller Beitrag', 'nami_id' => 1]);
        \App\Fee::create(['title' => 'Familienbeitrag', 'nami_id' => 2]);
        \App\Fee::create(['title' => 'Sozialermäßigt', 'nami_id' => 3]);
    }
}

<?php

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Unit::create(['title' => 'Tage', 'type' => 'date']);
        \App\Unit::create(['title' => 'Wochen', 'type' => 'date']);
        \App\Unit::create(['title' => 'Monate', 'type' => 'date']);
        \App\Unit::create(['title' => 'Jahre', 'type' => 'date']);
    }
}

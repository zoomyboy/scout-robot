<?php

use Illuminate\Database\Seeder;

class WaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Way::create(['title' => 'E-Mail']);
        \App\Way::create(['title' => 'Post']);
    }
}

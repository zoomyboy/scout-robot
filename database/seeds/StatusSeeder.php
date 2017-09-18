<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Status::create(['title' => 'Nicht bezahlt']);
        \App\Status::create(['title' => 'Rechnung versendet']);
        \App\Status::create(['title' => 'Bezahlt']);
    }
}

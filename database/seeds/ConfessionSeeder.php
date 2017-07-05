
<?php

use Illuminate\Database\Seeder;

class ConfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Confession::create(['title' => 'Römisch-Katholisch']);
        \App\Confession::create(['title' => 'Evangelisch / Protestantisch']);
        \App\Confession::create(['title' => 'Orthodox']);
        \App\Confession::create(['title' => 'Freikirchlich']);
        \App\Confession::create(['title' => 'Andere christliche']);
        \App\Confession::create(['title' => 'Jüdisch']);
        \App\Confession::create(['title' => 'Muslimisch']);
        \App\Confession::create(['title' => 'Sonstige']);
        \App\Confession::create(['title' => 'Neuapostolisch']);
    }
}


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
        \App\Confession::create(['title' => 'Römisch-Katholisch', 'nami_id' => 1, 'nami_title' => 'römisch-katholisch']);
        \App\Confession::create(['title' => 'Evangelisch / Protestantisch', 'nami_id' => 2, 'nami_title' => 'evangelisch / protestantisch']);
        \App\Confession::create(['title' => 'Orthodox', 'nami_id' => 3, 'nami_title' => 'orthodox']);
        \App\Confession::create(['title' => 'Freikirchlich', 'nami_id' => 4, 'nami_title' => 'freikirchlich']);
        \App\Confession::create(['title' => 'Andere christliche', 'nami_id' => 5, 'nami_title' => 'andere christliche']);
        \App\Confession::create(['title' => 'Jüdisch', 'nami_id' => 6, 'nami_title' => 'jüdisch']);
        \App\Confession::create(['title' => 'Muslimisch', 'nami_id' => 7, 'nami_title' => 'muslimisch']);
        \App\Confession::create(['title' => 'Sonstige', 'nami_id' => 8, 'nami_title' => 'sonstige']);
        \App\Confession::create(['title' => 'Neuapostolisch', 'nami_id' => 11, 'nami_title' => 'neuapostolisch']);
        \App\Confession::create(['title' => 'Ohne Konfession', 'nami_id' => 9, 'nami_title' => 'ohne Konfession']);
    }

}

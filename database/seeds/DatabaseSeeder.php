<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RightSeeder::class);
        $this->call(UserGroupSeeder::class);
        $this->call(UserSeeder::class);
    }
}

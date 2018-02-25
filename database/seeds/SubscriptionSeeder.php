<?php

use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Fee::find(1)->subscriptions()->create(['title' => 'Voll', 'amount' => 5000]);
        \App\Fee::find(2)->subscriptions()->create(['title' => 'Familie', 'amount' => 4000]);
        \App\Fee::find(3)->subscriptions()->create(['title' => 'Sozial', 'amount' => 3500]);
    }
}

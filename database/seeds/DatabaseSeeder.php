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
        $this->call(FeeSeeder::class);
		$this->call(SubscriptionSeeder::class);
        $this->call(ActivitySeeder::class);
		$this->call(NationalitySeeder::class);
        $this->call(RightSeeder::class);
        $this->call(UsergroupSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(ConfessionSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(ConfSeeder::class);
        $this->call(WaySeeder::class);

        if (app()->environment() != 'local' && app()->environment() != 'production') {
            $this->call(MemberSeeder::class);
        }

        $this->call(PaymentSeeder::class);
        $this->call(UnitSeeder::class);
    }
}

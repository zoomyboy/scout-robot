<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$a = [
			\App\Activity::create(['title' => 'Mitglied', 'nami_id' => 1, 'is_payable' => true]),
        	\App\Activity::create(['title' => 'passive Mitgliedschaft', 'nami_id' => 39, 'is_payable' => true]),
        	\App\Activity::create(['title' => 'ElternvertreterIn', 'nami_id' => 4, 'is_payable' => false]),
			\App\Activity::create(['title' => 'GeschäftsführerIn', 'nami_id' => 19, 'is_payable' => true]),
			\App\Activity::create(['title' => 'KassenprüferIn', 'nami_id' => 21, 'is_payable' => true]),
			\App\Activity::create(['title' => 'KassiererIn', 'nami_id' => 20, 'is_payable' => true]),
			\App\Activity::create(['title' => 'KuratIn', 'nami_id' => 11, 'is_payable' => true]),
			\App\Activity::create(['title' => 'LeiterIn', 'nami_id' => 6, 'is_payable' => true]),
			\App\Activity::create(['title' => 'Leitungsteam-SprecherIn', 'nami_id' => 5, 'is_payable' => true]),
			\App\Activity::create(['title' => 'MaterialwartIn', 'nami_id' => 23, 'is_payable' => true]),
			\App\Activity::create(['title' => 'ReferentIn', 'nami_id' => 10, 'is_payable' => true]),
			\App\Activity::create(['title' => 'SprecherIn', 'nami_id' => 2, 'is_payable' => true]),
			\App\Activity::create(['title' => 'VertreterIn', 'nami_id' => 24, 'is_payable' => false]),
			\App\Activity::create(['title' => 'Vorsitzende/r', 'nami_id' => 13, 'is_payable' => true]),
			\App\Activity::create(['title' => 'Sonst. Mitarbeiter (€)', 'nami_id' => 40, 'is_payable' => true]),
			\App\Activity::create(['title' => 'Sonst. Mitarbeiter', 'nami_id' => 41, 'is_payable' => false]),
			\App\Activity::create(['title' => 'Schnuppermitglied', 'nami_id' => 35, 'is_payable' => false])
		];

		$g = [
			\App\Group::create(['title' => 'Biber', 'nami_id' => 49, 'is_group' => true, 'group_order' => 1]),
			\App\Group::create(['title' => 'Wölfling', 'nami_id' => 1, 'is_group' => true, 'group_order' => 2]),
			\App\Group::create(['title' => 'Jungpfadfinder', 'nami_id' => 2, 'is_group' => true, 'group_order' => 3]),
			\App\Group::create(['title' => 'Pfadfinder', 'nami_id' => 3, 'is_group' => true, 'group_order' => 4]),
			\App\Group::create(['title' => 'Rover', 'nami_id' => 4, 'is_group' => true, 'group_order' => 5]),
			\App\Group::create(['title' => 'Sonstige', 'nami_id' => 48, 'is_group' => false, 'group_order' => null]),
			\App\Group::create(['title' => 'Vorstand', 'nami_id' => 5, 'is_group' => false, 'group_order' => null])
		];

		\App\Activity::get()->each(function($a) use ($g) {
			$a->groups()->attach($g[0]);
			$a->groups()->attach($g[1]);
			$a->groups()->attach($g[2]);
			$a->groups()->attach($g[3]);
			$a->groups()->attach($g[4]);
			$a->groups()->attach($g[5]);
			$a->groups()->attach($g[6]);
		});

		\App\Activity::whereIn('id', ['1', '8', '9', '12'])->get()->each(function($a) use ($g) {
			$a->groups()->detach($g[5]);
			$a->groups()->detach($g[6]);
		});

		// Vorsitzende/r hat keine Stufe
		\App\Activity::where('id', 14)->first()->groups()->detach($g[0]);
		\App\Activity::where('id', 14)->first()->groups()->detach($g[1]);
		\App\Activity::where('id', 14)->first()->groups()->detach($g[2]);
		\App\Activity::where('id', 14)->first()->groups()->detach($g[3]);
		\App\Activity::where('id', 14)->first()->groups()->detach($g[4]);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;
use App\Conf;

class ConfSeeder extends Seeder
{
	private $confs = [
		'default_country_id',
		'default_region_id',
		'default_keepdata' => false,
		'default_sendnewspaper' => false,
		'letterKontoName',
		'letterIban',
		'letterBic',
		'letterZweck',
		'includeFamilies' => false,
		'deadlinenr',
		'deadlineunit_id',
		'letterFrom',
		'groupname',
		'personName',
		'personTel',
		'personMail',
		'personFunction',
		'personAddress',
		'personZip',
		'personCity',
		'website',
		'emailHeading',
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Schema::table('confs', function(Blueprint $table) {
			foreach($this->confs as $key => $v) {
				if (is_integer($key)) {
					$table->string($v)->nullable();
				} else {
					$table->string($key)->nullable()->default($v);
				}
			}
		});

		if (is_null(\App\Country::where('code', config('seed.default_country'))->first())) {
			throw new \Exception('Default country  not found in countries table. You should run a seeder or create the table!');
		}

		Conf::create([
			'default_country_id' => \App\Country::where('code', config('seed.default_country'))->first()->id,
			'default_region_id' => null,
			'default_keepdata' => false,
			'default_sendnewspaper' => false,
			'emailBillText' => 'Im Anhang dieser Mail befindet sich die Jahresrechnung für {{ $members }}. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailRememberText' => 'Leider haben wir bisher für die ausstehenden Beträge keinen Zahlungseingang feststellen können. Daher senden wir dir mit dieser E-Mail eine Zahlungserinnerung im Anhang. Bitte begleiche diese bis zum angegebenen Datum.',
			'emailGreeting' => 'Gut Pfad | {{ $groupname }}'
		]);
    }
}

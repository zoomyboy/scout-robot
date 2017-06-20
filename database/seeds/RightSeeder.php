<?php

use Illuminate\Database\Seeder;
use App\Right;

class RightSeeder extends Seeder
{
	private $rights = [
		['login', 'Einloggen', 'In Scout Robot mit seinen eigenen Benutzerdaten einloggen']
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		foreach($this->rights as $right) {
			Right::create(array_combine(['key', 'title', 'help'], $right));
		}
    }
}

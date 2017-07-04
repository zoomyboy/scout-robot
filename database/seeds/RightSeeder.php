<?php

use Illuminate\Database\Seeder;
use App\Right;

class RightSeeder extends Seeder
{
	private $rights = [
		['login', 'Einloggen', 'In Scout Robot mit seinen eigenen Benutzerdaten einloggen'],
		['user', 'Benutzer bearbeiten', 'Andere Benutzer bearbeiten, anlegen und löschen über den Benutzer-Link im unteren Bereich der linken Navigationsleiste'],
		['usergroup', 'Benutzergruppen bearbeiten', 'Benutzergruppen und deren Rechte bearbeiten, anlegen und löschen über den Benutzergruppeen-Link im unteren Bereich der linken Navigationsleiste'],
		['config', 'Globale Konfiguration bearbeiten', 'Zugriff auf die globalen Einstellungen im unteren Bereich der rechten Navigationsleiste'],
		['member.manage', 'Mitglieder verwalten', 'Mitglieder erstellen, bearbeiten und löschen'],
		['member.overview', 'Mitglieder einsehen', 'Mitgliederübersicht anzeigen'],
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

<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\User;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClientInformationTest extends FeatureTestCase {

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();

        Config::set('seed.default_usergroup', 'NewUserGroup');
        Config::set('seed.default_username', 'Admin');
        Config::set('seed.default_userpw', 'admin22');
        Config::set('seed.default_usermail', 'admin@example.tz');
        Config::set('seed.default_country', 'Algerien');

        $this->runSeeder(\RightSeeder::class);
        $p = $this->create('usergroup', ['title' => 'NewUserGroup'])->rights()->sync([2,3]);
        $this->runSeeder(\CountrySeeder::class);
        $this->runSeeder(\RegionSeeder::class);
        $this->runSeeder(\ConfSeeder::class);
        $this->runSeeder('WaySeeder');
    }

    /** @test */
    public function it_can_get_client_info() {
        $user = $this->authAsApi();
        $user->update(['usergroup_id' => 1]);

        $this->getApi('info')
            ->assertSuccess()
            ->assertJson(['conf' => [
                'default_country' => [
                    'id' => 2,
                    'title' => 'Algerien'
                ],
                'default_keepdata' => false,
                'default_region' => null,
                'default_sendnewspaper' => false
            ], 'user' => [
                'id' => 1,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'usergroup' => [
                    'id' => 1,
                    'title' => 'NewUserGroup',
                    'rights' => [
                        ['id' => 2, 'key' => 'user', 'title' => 'Benutzer bearbeiten'],
                        ['id' => 3, 'key' => 'usergroup', 'title' => 'Benutzergruppen bearbeiten']
                    ]
                ]
            ]]);
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();

        $this->getApi('info')->assertUnauthorized();
    }
}

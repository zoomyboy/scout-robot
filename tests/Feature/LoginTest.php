<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\User;
use Illuminate\Support\Facades\Config;
use Tests\FeatureTestCase;

class LoginTest extends FeatureTestCase {
    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();

        Config::set('seed.default_usergroup', 'SA');
        Config::set('seed.default_username', 'Admin');
        Config::set('seed.default_userpw', 'admin22');
        Config::set('seed.default_usermail', 'admin@example.tz');

        $this->runSeeder(\RightSeeder::class);
        $this->runSeeder(\UsergroupSeeder::class);
        $this->runSeeder(\UserSeeder::class);
    }

    /** @test */
    public function it_redirects_to_login_page_if_not_logged_in() {
        $this->withExceptionHandling();

        $this->get('/')->assertRedirect('/login');
    }

    /** @test */
    public function it_redirects_to_home_page_if_logged_in() {
        $this->auth();
        $this->get('/login')->assertRedirect('/');
    }

    /** @test */
    public function it_loggs_in_successfully() {
        $user = User::first();
        $this->postJson('/login', ['email' => config('seed.default_usermail'), 'password' => config('seed.default_userpw')]);

        $this->assertInstanceOf(\App\User::class, auth()->user());

        $this->get('/')->assertStatus(200);
    }

    /** @test */
    public function it_loggs_out() {
        $this->auth();
        $this->get('/logout')->assertRedirect('/');

        $this->assertNull(auth()->guard('web')->user());
    }

    /** @test */
    public function it_validates_login() {
        $user = User::first();

        $this->postJson('/login', ['email' => config('seed.default_usermail').'A', 'password' => config('seed.default_userpw').'A'])
            ->assertStatus(422)
            ->assertJson(['email' => __('auth.failed')]);

        $this->assertNull(auth()->user());
    }
}

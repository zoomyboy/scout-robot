<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function it_can_get_client_info() {
        $user = $this->authAsApi(User::first());

        $user->usergroup->rights()->sync([1]);

        $this->getApi('me')
            ->assertSuccess()
            ->assertExactJson([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.tz',
                'usergroup' => [
                    'id' => 1,
                    'title' => 'NewUserGroup',
                    'rights' => [
                        ['id' => 1, 'key' => 'login', 'title' => 'Einloggen']
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();

        $this->getApi('me')->assertUnauthorized();
    }
}

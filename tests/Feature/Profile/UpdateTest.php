<?php

namespace Tests\Feature\Profile;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function a_user_can_edit_his_name_and_email() {
        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}", [
            'name' => 'Admin2',
            'email' => 'admin2@example.com'
        ])->assertSuccess();

        $this->assertDatabaseHas('users', [ 'name' => 'Admin2', 'email' => 'admin2@example.com']);
        $this->assertCanLogin('admin2@example.com', 'admin22');
    }

    /** @test */
    public function it_cannot_edit_a_user_that_doesnt_exist() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi('profile/5', [
            'name' => 'Admin2',
            'email' => 'admin2@example.com'
        ])->assertNotFound();

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_can_only_edit_his_own_profile() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());
        $otherUser = $this->create('user');

        $this->patchApi("profile/{$otherUser->id}", [
            'name' => 'Admin3',
            'email' => 'admin3@example.com'
        ])->assertForbidden();

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();

        $user = User::first();

        $this->patchApi("profile/{$user->id}", [
            'name' => 'Admin3',
            'email' => 'admin3@example.com'
        ])->assertUnauthorized();

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_has_to_enter_a_valid_email_address() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}", [
            'name' => 'Admin3',
            'email' => 'notaemail'
        ])->assertValidationFailedWith('email');

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_has_to_enter_a_name_and_an_email_address() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}", [
            'name' => '',
            'email' => ''
        ])
            ->assertValidationFailedWith('email')
            ->assertValidationFailedWith('name');

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_cannot_edit_his_password() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}", [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg'
        ])->assertSuccess();

        $this->assertCanLogin('test@example.com', 'admin22');
        $this->assertCannotLogin('test@example.com', 'abcdefg');
    }

    /** @test */
    public function it_cannot_edit_his_usergroup() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}", [
            'name' => 'Test',
            'email' => 'test@example.com',
            'usergroup' => '2',
            'usergroup_id' => '2'
        ])->assertSuccess();

        $this->assertDatabaseHas('users', ['usergroup_id' => 1]);
    }

    private function assertUserHasntChanged($user = null) {
        $this->assertDatabaseHas('users', $user->getAttributes());
    }
}

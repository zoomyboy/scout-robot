<?php

namespace Tests\Feature\ProfilePassword;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function a_user_can_edit_his_password() {
        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg'
        ])->assertSuccess();

        $this->assertCanLogin('admin@example.tz', 'abcdefg');
    }

    /** @test */
    public function it_cannot_edit_a_user_that_doesnt_exist() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());
        
        $this->patchApi("profile/5/password", [
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg'
        ])->assertNotFound();

        $this->assertUserHasntChanged();
    }

    /** @test */
    public function it_can_only_edit_his_own_profile() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $otherUser = $this->create('user');
        $oldUserPassword = $otherUser->password;

        $this->patchApi("profile/{$otherUser->id}/password", [
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg'
        ])->assertForbidden();

        $otherUser = User::find($otherUser->id);
        $this->assertEquals($oldUserPassword, $otherUser->password);
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();

        $user = User::first();

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg'
        ])->assertUnauthorized();

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_has_to_enter_a_valid_password() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}/password", [
            'password' => '',
            'password_confirmation' => ''
        ])
            ->assertValidationFailedWith('password')
            ->assertValidationFailedWith('password_confirmation');

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abccdefg',
            'password_confirmation' => ''
        ])
            ->assertValidationFailedWith('password_confirmation');

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abccdefg',
            'password_confirmation' => ''
        ])
            ->assertValidationFailedWith('password_confirmation');

        $this->patchApi("profile/{$user->id}/password", [
            'password' => '',
            'password_confirmation' => 'abcdefg'
        ])
            ->assertValidationFailedWith('password');

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abcsvdsvdsv',
            'password_confirmation' => 'abcdef'
        ])
            ->assertValidationFailedWith('password');

        $this->patchApi("profile/{$user->id}/password", [
            'password' => 'abc',
            'password_confirmation' => 'abc'
        ])
            ->assertValidationFailedWith('password');

        $this->assertUserHasntChanged($user);
    }

    /** @test */
    public function it_cannot_edit_his_email() {
        $this->withExceptionHandling();

        $user = $this->authAsApi(User::first());

        $this->patchApi("profile/{$user->id}/password", [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'abcdefg',
            'password_confirmation' => 'abcdefg',
            'usergroup' => '2',
            'usergroup_id' => '2'
        ])->assertSuccess();

        $this->assertCanLogin('admin@example.tz', 'abcdefg');
        $this->assertDatabaseHas('users', ['name' => 'Admin', 'id' => $user->id, 'usergroup_id' => 1]);
    }

    private function assertUserHasntChanged($user = null) {
        $user = $user ?: User::first();

        $this->assertDatabaseHas('users', [
            'name' => 'Admin',
            'id' => $user->id,
            'usergroup_id' => 1,
            'email' => 'admin@example.tz'
        ]);
        $this->assertCanLogin('admin@example.tz', 'admin22');
    }

    private function assertCanLogin($email, $password, $guard = 'web') {
        $this->assertTrue(Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]));
    }
}

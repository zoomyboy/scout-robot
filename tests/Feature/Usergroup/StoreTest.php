<?php

namespace Tests\Feature\Usergroup;

use Tests\TestCase;
use App\User;
use App\Usergroup;
use App\Right;
use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsergroupStoreTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_usergroup() {
        $this->withExceptionHandling();
        $user = $this->authAsApi(User::first());

        $this->postApi('usergroup', [
            'title' => 'abcdefg',
            'rights' => [2, 3]
        ])->assertSuccess();

        $usergroup = Usergroup::whereTitle('abcdefg')->first();
        $this->assertNotNull($usergroup);
        $this->assertEquals([2, 3], $usergroup->rights->pluck('id')->toArray());

        $this->postApi('usergroup', [
            'title' => 'abcdefgh',
            'rights' => []
        ])->assertSuccess();

        $usergroup = Usergroup::whereTitle('abcdefgh')->first();
        $this->assertNotNull($usergroup);
        $this->assertEmpty($usergroup->rights->pluck('id'));
    }

    /** @test */
    public function it_can_only_create_usergroups_if_it_has_permission() {
        $this->withExceptionHandling();
        $user = $this->authAsApi(User::first());

        $user->usergroup->rights()->detach(Right::where('key', 'usergroup')->first());

        $this->postApi('usergroup', [
            'title' => 'abcdefg',
            'rights' => [2, 3]
        ])->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();

        $this->postApi('usergroup', [
            'title' => 'abcdefg',
            'rights' => [2, 3]
        ])->assertUnauthorized();
    }

    /** @test */
    public function it_has_to_enter_a_name() {
        $this->withExceptionHandling();
        $this->authAsApi(User::first());

        $this->postApi('usergroup', [
            'title' => '',
            'rights' => [2, 3]
        ])->assertValidationFailedWith('title');
    }

    /** @test */
    public function it_has_to_enter_a_valid_right() {
        $this->withExceptionHandling();
        $this->authAsApi(User::first());

        $this->postApi('usergroup', [
            'title' => 'Abc',
            'rights' => [3, 999]
        ])->assertValidationFailedWith('rights.1');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Usergroup;
use App\Right;
use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyTest extends TestCase {

    use RefreshDatabase;
    
    /** @test */
    public function it_can_delete_a_usergroup() {
        $this->withExceptionHandling();
        $this->authAsApi();
        $usergroup = $this->create('usergroup');

        $this->deleteApi("usergroup/{$usergroup->id}")->assertSuccess();

        $this->assertDatabaseMissing('usergroups', ['id' => $usergroup->id]);
    }

    /** @test */
    public function it_can_only_delete_usergroups_if_it_has_permission() {
        $this->withExceptionHandling();
        $user = $this->authAsApi(User::first());

        $usergroup = $this->create('usergroup');
        $usergroup->rights()->detach(Right::where('key', 'usergroup')->first());
        $user->update(['usergroup_id' => $usergroup->id]);

        $this->deleteApi("usergroup/{$usergroup->id}")->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_be_a_guest() {
        $this->withExceptionHandling();
        $usergroup = $this->create('usergroup');

        $this->deleteApi("usergroup/{$usergroup->id}")->assertUnauthorized();
    }

    /** @test */
    public function it_can_only_delete_a_usergroup_that_exists() {
        $this->withExceptionHandling();
        $this->authAsApi();

        $this->deleteApi("usergroup/200")->assertNotFound();
    }

    /** @test */
    public function it_can_only_delete_a_usergroup_that_has_no_members() {
        $this->withExceptionHandling();
        $this->authAsApi();

        $usergroup = $this->create('usergroup');
        $newUser = $this->create('user', ['usergroup_id' => $usergroup->id]);


        $this->deleteApi("usergroup/{$usergroup->id}")->assertValidationFailedWith("id");
    }
}

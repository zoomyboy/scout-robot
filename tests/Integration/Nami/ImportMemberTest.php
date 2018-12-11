<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Member as MemberManager;
use Tests\IntegrationTestCase;

class ImportMemberTest extends IntegrationTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
    }

    /** @test */
    public function the_manager_can_store_a_nami_response_as_a_member() {
        $this->fakeOnlineNamiMembers([
            []
        ]);

        $manager = app(MemberManager::class);

        $manager->pull(5678);

        $this->assertDatabaseHas('members', [
            'country_id' => 1,
            'email' => 'member@test.de',
            'email_parents' => 'eltern@test.de',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'nickname' => 'Spitz',
            'nami_id' => 5678,
            'nationality_id' => 2,
            'joined_at' => '2015-05-27 00:00:00',
            'birthday' => '2005-12-28 00:00:00',
            'keepdata' => true,
            'sendnewspaper' => true,
            'address' => 'Strasse 1',
            'zip' => 42777,
            'city' => 'City',
            'other_country' => 'Andere',
            'further_address' => 'Zusatz',
            'phone' => '+49 212 319345',
            'mobile' => '+49 163 1725774',
            'business_phone' => '+49 222 33333',
            'fax' => '+49 212 4455555',
            'active' => true,
            'gender_id' => 1,
            'region_id' => 1,
            'confession_id' => 1,
            'subscription_id' => 1
        ]);
    }

    /** @test */
    public function it_doesnt_set_the_gender_or_region_when_nami_returns_null_value() {
        $noGender = $this->create('Gender', ['is_null' => true, 'nami_id' => 89]);
        $noRegion = $this->create('Region', ['is_null' => true, 'nami_id' => 90]);

        $this->fakeOnlineNamiMembers([
            ['geschlechtId' => 89, 'regionId' => 90]
        ]);

        $manager = app(MemberManager::class);

        $manager->pull(5678);

        $this->assertDatabaseHas('members', [
            'nami_id' => 5678,
            'gender_id' => null,
            'region_id' => null
        ]);
    }

    /** @test */
    public function it_stores_inactive_nami_members_as_inactive() {
        $this->fakeOnlineNamiMembers([
            ['status' => 'Inaktiv']
        ]);

        $manager = app(MemberManager::class);
        $manager->pull(5678);

        $this->assertDatabaseHas('members', [
            'nami_id' => 5678,
            'active' => false
        ]);
    }
}

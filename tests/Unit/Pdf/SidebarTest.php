<?php

namespace Tests\Unit\Pdf;

use App\Status;
use App\Subscription;
use Tests\UnitTestCase;
use App\Facades\Setting;
use App\Pdf\Generator\LetterGenerator;
use App\Pdf\Repositories\BillPageRepository;
use App\Pdf\Repositories\RememberPageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SidebarTest extends UnitTestCase {
    use RefreshDatabase;

    public function repositoryProvider() {
        return [[BillPageRepository::class], [RememberPageRepository::class]];
    }

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->createMembers();

        $this->members = collect([$this->create('Member')]);
    }

    /**
     * @test
     * @dataProvider repositoryProvider
     */
    public function it_gets_the_person_for_the_sidebar($repository) {
        Setting::set('personName', 'Stamm Silva');
        Setting::set('personFunction', 'Stammesvorstand');

        $page = new $repository($this->members, [
            'deadline' => '2018-01-01'
        ]);

        $this->assertContains('Stamm Silva', $page->getContactInfo());
        $this->assertContains('Stammesvorstand', $page->getContactInfo());
    }

    /**
     * @test
     * @dataProvider repositoryProvider
     */
    public function it_gets_the_address_for_the_sidebar($repository) {
        Setting::set('personAddress', 'Itterstr 3');
        Setting::set('personZip', '42719');
        Setting::set('personCity', 'Solingen');

        $page = new $repository($this->members, [
            'deadline' => '2018-01-01'
        ]);

        $this->assertContains('Itterstr 3', $page->getContactInfo());
        $this->assertContains('42719 Solingen', $page->getContactInfo());
    }

    /**
     * @test
     * @dataProvider repositoryProvider
     */
    public function it_gets_the_contact_info_for_the_sidebar($repository) {
        Setting::set('personTel', '0212445566');
        Setting::set('personMail', 'pille@stamm-silva.de');
        Setting::set('website', 'https://www.stamm-silva.de');

        $page = new $repository($this->members, [
            'deadline' => '2018-01-01'
        ]);

        $this->assertContains('0212445566', $page->getContactInfo());
        $this->assertContains('pille@stamm-silva.de', $page->getContactInfo());
        $this->assertContains('https://www.stamm-silva.de', $page->getContactInfo());
    }
}

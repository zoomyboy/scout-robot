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

class HeaderTest extends UnitTestCase {
    use RefreshDatabase;

    public function repositoryProvider() {
        return [[BillPageRepository::class], [RememberPageRepository::class]];
    }

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->createMembers();

        $this->members = collect([$this->create('Member', [
            'firstname' => 'Philipp',
            'lastname' => 'Lang',
            'zip' => 42719,
            'city' => 'Solingen',
            'address' => 'Itterstr 3'
        ])]);
    }

    /**
     * @test
     * @dataProvider repositoryProvider
     */
    public function it_gets_address_data_for_the_member($repository) {
        $page = new $repository($this->members, [
            'deadline' => request()->deadline
        ]);

        $headerAddress = $page->getHeaderAddress();

        $this->assertEquals([
            'Familie Lang',
            'Itterstr 3',
            '42719 Solingen'
        ], $page->getHeaderAddress());
    }
}

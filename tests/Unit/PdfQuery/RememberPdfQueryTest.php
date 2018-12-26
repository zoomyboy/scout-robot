<?php

namespace Tests\Unit\PdfQuery;

use App\Queries\RememberPdfQuery;
use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RememberPdfQueryTest extends IntegrationTestCase {
    public $members;

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->prepareMembers();

        $this->members = $this->createMany('Member', 2, [
            ['way_id' => 1],
            ['way_id' => 2]
        ]);
    }

    /** @test */
    public function it_gets_no_values_if_members_have_no_payments() {
        $this->assertCount(0, RememberPdfQuery::members()->get());
    }

    /** @test */
    public function it_gets_no_values_if_members_have_only_paid_payments() {
        $this->createPayment($this->members[0], 'Bezahlt', 'Voll', 2018);
        $this->createPayment($this->members[1], 'Bezahlt', 'Voll', 2018);

        $this->assertCount(0, RememberPdfQuery::members()->get());
    }

    /** @test */
    public function it_gets_no_values_if_members_have_zero_amount() {
        $zeroSubscription = $this->create('Subscription', ['amount' => 0, 'title' => 'Nichts']);

        $this->createPayment($this->members[0], 'Nicht bezahlt', 'Nichts', 2018);
        $this->createPayment($this->members[1], 'Rechnung versendet', 'Nichts', 2018);

        $this->assertCount(0, RememberPdfQuery::members()->get());
    }

    /** @test */
    public function it_gets_members_and_filters_for_ways() {
        $this->createPayment($this->members[0], 'Rechnung versendet', 'Voll', 2018);
        $this->createPayment($this->members[1], 'Rechnung versendet', 'Voll', 2018);

        $this->assertContains($this->members[0]->id, RememberPdfQuery::members()->filterWays([1])->get()->pluck('id'));
        $this->assertContains($this->members[1]->id, RememberPdfQuery::members()->filterWays([2])->get()->pluck('id'));
        $this->assertCount(2, RememberPdfQuery::members()->get());
    }
}

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

class PaymentsTest extends UnitTestCase {
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->createMembers();

        $this->members = collect([
            $this->create('Member', ['way_id' => 1, 'firstname' => 'Philipp', 'lastname' => 'Lang']),
            $this->create('Member', ['way_id' => 2, 'firstname' => 'Philipp2', 'lastname' => 'Lang2'])
        ]);
    }

    /** @test */
    public function it_gets_the_payments_of_a_bill() {
        $this->members[0]->createPayment([
            'subscription_id' => Subscription::title('Voll')->first()->id,
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'nr' => '2018'
        ]);
        $this->members[1]->createPayment([
            'subscription_id' => Subscription::title('Voll')->first()->id,
            'status_id' => Status::title('Rechnung versendet')->first()->id,
            'nr' => '2018'
        ]);

        $bill = new BillPageRepository($this->members, [
            'deadline' => '2018-01-01'
        ]);
        $remember = new RememberPageRepository($this->members, [
            'deadline' => '2018-01-01'
        ]);

        $this->assertEquals([
            'Beitrag 2018 für Philipp Lang' => '50,00 €'
        ], $bill->getPayments());
        $this->assertEquals([
            'Beitrag 2018 für Philipp2 Lang2' => '50,00 €'
        ], $remember->getPayments());
    }
}

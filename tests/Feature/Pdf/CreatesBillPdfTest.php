<?php

namespace Tests\Feature\Pdf;

use Storage;
use App\Status;
use App\Payment;
use App\Subscription;
use Tests\FeatureTestCase;

class CreatesBillPdfTest extends FeatureTestCase
{
    public $member;

    public function setUp()
    {
        parent::setUp();

        \Storage::fake('temp');
        $this->createConfigs();
        $this->createMembers();

        $this->member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        $this->member->createPayment([
            'status_id' => 1,
            'subscription_id' => 1,
            'nr' => 50
        ]);

        $this->member->createPayment([
            'status_id' => 2,
            'subscription_id' => 1,
            'nr' => 50
        ]);
    }

    /** @test */
    public function it_creates_a_bill_pdf_for_a_single_member_with_no_families()
    {
        $this->authAsApi();

        $this->postApi("member/{$this->member->id}/billpdf", [
            'includeFamilies' => false,
            'deadline' => null
        ])
            ->assertSee(Storage::disk('temp')->url('rechnung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("rechnung-fur-must.pdf");
    }

    /** @test */
    public function it_creates_a_bill_for_a_single_member_with_families()
    {
        $this->authAsApi();

        $this->postApi("member/{$this->member->id}/billpdf", [
            'includeFamilies' => true,
            'deadline' => null
        ])
            ->assertSee(Storage::disk('temp')->url('rechnung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("rechnung-fur-must.pdf");
    }

    /** @test */
    public function it_creates_a_remmeber_pdf_for_a_single_member_with_families()
    {
        $this->authAsApi();

        $this->postApi("member/{$this->member->id}/rememberpdf", [
            'includeFamilies' => true,
            'deadline' => null
        ])
            ->assertSee(Storage::disk('temp')->url('erinnerung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("erinnerung-fur-must.pdf");
    }

    /** @test */
    public function it_creates_a_remmeber_pdf_for_a_single_member_with_no_families()
    {
        $this->authAsApi();

        $this->postApi("member/{$this->member->id}/rememberpdf", [
            'includeFamilies' => false,
            'deadline' => null
        ])
            ->assertSee(Storage::disk('temp')->url('erinnerung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("erinnerung-fur-must.pdf");
    }
}

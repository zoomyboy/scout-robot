<?php

namespace Tests\Feature\Pdf;

use Storage;
use App\Status;
use App\Payment;
use App\Subscription;
use Tests\FeatureTestCase;

class CreatesBillPdfTest extends FeatureTestCase
{
    public function setUp()
    {
        parent::setUp();

        \Storage::fake('temp');
        $this->createConfigs();
        $this->createMembers();
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_no_families()
    {
        $this->authAsApi();

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        $member->createPayment([
            'status_id' => 1,
            'subscription_id' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => false, 'deadline' => null])
            ->assertSee(Storage::disk('temp')->url('rechnung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("rechnung-fur-must.pdf");
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_families()
    {
        $this->authAsApi();

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        $member->createPayment([
            'status_id' => 1,
            'subscription_id' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => true, 'deadline' => null])
            ->assertSee(Storage::disk('temp')->url('rechnung-fur-must.pdf'));

        Storage::disk('temp')->assertExists("rechnung-fur-must.pdf");
    }
}

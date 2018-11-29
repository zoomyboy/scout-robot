<?php

namespace Tests\Feature\Pdf;

use Tests\FeatureTestCase;
use App\Payment;
use App\Subscription;
use App\Status;

class CreatesBillPdfTest extends FeatureTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->runSeeder('DatabaseSeeder');
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_no_families()
    {
        $this->authAsApi();

        \Storage::fake('public');

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        \App\Payment::create([
            'status_id' => 1,
            'member_id' => $member->id,
            'subscription_id' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => false, 'deadline' => null])
            ->assertSuccess();

        \Storage::disk('public')->assertExists("pdf/rechnung-fur-must.pdf");
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_families()
    {
        $this->authAsApi();

        \Storage::fake('public');

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        \App\Payment::create([
            'status_id' => 1,
            'member_id' => $member->id,
            'subscription_id' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => true, 'deadline' => null])
            ->assertSuccess();

        \Storage::disk('public')->assertExists("pdf/rechnung-fur-must.pdf");
    }
}

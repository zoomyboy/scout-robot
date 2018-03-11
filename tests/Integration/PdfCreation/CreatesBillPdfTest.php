<?php

namespace Tests\Integration\PdfCreation;

use Tests\IntegrationTestCase;
use App\Payment;
use App\Subscription;
use App\Status;

class CreatesBillPdfTest extends IntegrationTestCase {
    public function setUp() {
        parent::setUp();

        $this->runSeeder('DatabaseSeeder');
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_no_families() {
        $this->authAsApi();

        \Storage::fake('public');

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        $this->createPayment($member, [
            'status' => 1,
            'subscription' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => false, 'deadline' => null])
            ->assertSuccess();

        \Storage::disk('public')->assertExists("pdf/rechnung-fuer-must.pdf");
    }

    /** @test */
    public function it_creates_a_pdf_for_a_single_member_with_families() {
        $this->authAsApi();

        \Storage::fake('public');

        $member = $this->create('Member', ['firstname' => 'Max', 'lastname' => 'Must']);

        $this->createPayment($member, [
            'status' => 1,
            'subscription' => 1,
            'nr' => 50
        ]);

        $this->postApi("member/$member->id/billpdf", ['includeFamilies' => true, 'deadline' => null])
            ->assertSuccess();

        \Storage::disk('public')->assertExists("pdf/rechnung-fuer-must.pdf");
    }
}

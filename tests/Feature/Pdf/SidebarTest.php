<?php

namespace Tests\Integration;

use Setting;
use App\Status;
use App\Member;
use \Mockery as M;
use App\Subscription;
use Tests\FeatureTestCase;
use App\Pdf\Generator\LetterGenerator;
use App\Pdf\Repositories\BillContentRepository;

class SidebarTest extends FeatureTestCase {
    public $pdf;
    public $member;

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->createMembers();
        $this->mockPdf();

        $this->authAsApi();

        $this->member = $this->create('Member');
        $this->member->createPayment([
            'subscription_id' => Subscription::title('Voll')->first(),
            'status_id' => Status::title('Rechnung versendet')->first(),
            'nr' => '2018'
        ]);
    }

    /** @test */
    public function it_gets_the_person_for_the_sidebar() {
        Setting::set('personName', 'Stamm Silva');
        Setting::set('personFunction', 'Stammesvorstand');

        $this->shouldGeneratePdf(function($pdf) {
            return in_array('Stamm Silva', $pdf->getContactInfo())
            && in_array('Stammesvorstand', $pdf->getContactInfo())
            ;
        });

        $this->generate();
    }

    /** @test */
    public function it_gets_the_address_for_the_sidebar() {
        Setting::set('personAddress', 'Itterstr 3');
        Setting::set('personZip', '42719');
        Setting::set('personCity', 'Solingen');

        $this->shouldGeneratePdf(function($pdf) {
            return in_array('Itterstr 3', $pdf->getContactInfo())
            && in_array('42719 Solingen', $pdf->getContactInfo())
            ;
        });

        $this->generate();
    }

    /** @test */
    public function it_gets_the_contact_info_for_the_sidebar() {
        Setting::set('personTel', '0212445566');
        Setting::set('personMail', 'pille@stamm-silva.de');
        Setting::set('website', 'https://www.stamm-silva.de');

        $this->shouldGeneratePdf(function($pdf) {
            return in_array('0212445566', $pdf->getContactInfo())
            && in_array('pille@stamm-silva.de', $pdf->getContactInfo())
            && in_array('https://www.stamm-silva.de', $pdf->getContactInfo())
            ;
        });

        $this->generate();
    }

    private function generate($payload = []) {
        $payload = array_merge($this->defaultPayload(), $payload);

        $this->postApi('member/'.$this->member->id.'/billpdf', $payload)
            ->assertSee('testdokument.pdf');
    }

    private function shouldGeneratePdf($args, $times = 1) {
        $pdfFilename = 'Rechnung für '.$this->member->lastname;

        $this->pdf->shouldReceive('addPage')->withArgs($args)->times($times)
            ->andReturn($this->pdf);
        $this->pdf->shouldReceive('generate')
            ->with($pdfFilename)
            ->andReturn('testdokument.pdf');

        $this->app->instance(LetterGenerator::class, $this->pdf);
    }

    private function mockPdf() {
        $this->pdf = M::mock(LetterGenerator::class);

    }

    private function defaultPayload() {
        return [
            'deadline' => '2018-12-24',
            'includeFamilies' => true
        ];
    }
}

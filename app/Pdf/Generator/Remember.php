<?php

namespace App\Pdf\Generator;

use App\Pdf\Interfaces\LetterSidebarInterface;
use App\Pdf\Repositories\RememberContentRepository;
use App\Pdf\Traits\HasDate;
use App\Pdf\Traits\HasHeader;
use App\Pdf\Traits\HasSidebar;
use App\Pdf\Traits\HasSubject;
use Carbon\Carbon;

class Remember extends GlobalPdf {

    use HasHeader;
    use HasSidebar;
    use HasSubject;
    use HasDate;

    public $members;
    public $deadline;
    public $content;
    public $sidebar;

    public function __construct($members, $atts, LetterSidebarInterface $sidebar) {
        parent::__construct();
        $this->content = new RememberContentRepository();

        $this->sidebar = $sidebar;
        $this->members = $members;
        $this->deadline = $atts['deadline'] ? Carbon::parse($atts['deadline'])->format('d.m.Y') : '';
    }

    public function handle($filename) {
        foreach($this->members as $member) {
            $this->pdf->AddPage();
            $this->generateHeader($member[0]);

            $this->generateSubject();
            $this->generateDate();

            $this->pdf->SetFont('OpenSans', '', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 10, utf8_decode($this->content->getGreeting($member[0]->lastname)).',', 0, 1);
            $this->pdf->MultiCell(0, 5, $this->formatStringWithEuro($this->content->getIntro($member)), 0, 1);

            $this->pdf->Cell(0, 5, '', 0, 1);
            foreach ($member as $m) {
                foreach($m->payments()->where('status_id', 2)->get() as $p) {
                    $this->pdf->cell(110, 5, utf8_decode('Beitrag '.$p->nr.' für '.$m->firstname.' '.$m->lastname), 0, 0);
                    $this->pdf->cell(0, 5, utf8_decode(number_format($p->subscription->amount / 100, 2, ',', '.')).' '.EURO, 0, 1, 'R');
                }
            }

            $this->pdf->cell(0, 1, '', 'B', 1);

            $this->pdf->SetFont('OpenSans', 'B', 10); 
            $this->pdf->cell(110, 7, 'Gesamt', 0, 0);
            $this->pdf->cell(0, 7, utf8_decode($member->totalAmount([2])).' '.EURO, 0, 1, 'R');

            $this->pdf->SetFont('OpenSans', '', 10); 
            $this->pdf->cell(0, 3, '', '', 1);
            $this->pdf->MultiCell(0, 5, utf8_decode('Da von Ihnen bislang leider keine Zahlung auf dem unten angegebenen Konto eingegangen ist, erinnern wir Sie nun freundlichst daran, den Betrag von'), 0, 1);
            $this->pdf->SetFont('OpenSans', 'B', 10); 
            $this->pdf->Cell(0, 8, utf8_decode($member->totalAmount([2])).' '.EURO, 0, 1);
            $this->pdf->SetFont('OpenSans', '', 10); 

            if ($this->deadline) {
                $this->pdf->Write(8, utf8_decode('bis zum '));
                $this->pdf->SetFont('OpenSans', 'B', 10); 
                $this->pdf->Write(8, utf8_decode($this->deadline.' '));
                $this->pdf->SetFont('OpenSans', '', 10); 
            }

            $this->pdf->Cell(0, 8, utf8_decode(
                'auf folgendes Konto zu überweisen:'
            ), 0, 1);

            $this->pdf->Cell(60, 5, utf8_decode('Kontoinhaber:'), 0, 0);
            $this->pdf->Cell(40, 5, utf8_decode($this->config->groupname), 0, 1, 'L');
            $this->pdf->Cell(60, 5, utf8_decode('IBAN:'), 0, 0);
            $this->pdf->Cell(40, 5, utf8_decode($this->config->letterIban), 0, 1, 'L');
            $this->pdf->Cell(60, 5, utf8_decode('BIC:'), 0, 0);
            $this->pdf->Cell(40, 5, utf8_decode($this->config->letterBic), 0, 1, 'L');
            $this->pdf->Cell(60, 5, utf8_decode('Verwendungszweck:'), 0, 0);
            $this->pdf->Cell(40, 5, utf8_decode(str_replace('[name]', $m->lastname, $this->config->letterZweck)), 0, 1, 'L');

            $this->pdf->Cell(0, 5, '', 0, 1);
            $this->pdf->MultiCell(0, 5, utf8_decode('Bitte nehmen Sie zur Kenntnis, dass der für jedes Mitglied obligatorische Versicherungsschutz über die DPSG nur dann für Ihr Kind / Ihre Kinder gilt, wenn der Mitgliedsbeitrag bezahlt wurde. Wenn dies nicht geschieht, müssen wir Ihr Kind / Ihre Kinder von allen Pfadfinderaktionen ausschließen. Dazu gehören sowohl die Gruppenstunden sowie Tagesaktionen als auch mehrtägige Lager. Bei Fragen zur Rechnung können Sie mich auch persönlich erreichen unter:'));

            $this->pdf->Cell(0, 5, '', 0, 1);
            if ($this->config->personName) {
                $this->pdf->Cell(0, 5, utf8_decode('Name: '.$this->config->personName), 0, 1, 'L');
            }
            if ($this->config->personTel) {
                $this->pdf->Cell(0, 5, utf8_decode('Tel: '.$this->config->personTel), 0, 1, 'L');
            }
            if ($this->config->personMail) {
                $this->pdf->Cell(0, 5, utf8_decode('Mail: '.$this->config->personMail), 0, 1, 'L');
            }

            if ($this->config->personName && $this->config->personFunction) {
                $this->pdf->Cell(0, 5, '', 0, 1);
                $this->pdf->Cell(0, 5, utf8_decode('Viele Grüße'), 0, 1, 'L');
                $this->pdf->Cell(0, 5, '', 0, 1);
                $this->pdf->Cell(0, 5, utf8_decode($this->config->personName), 0, 1, 'L');
                $this->pdf->Cell(0, 5, utf8_decode($this->config->personFunction), 0, 1, 'L');
            }

            $this->pdf->Image(resource_path('img/end.png'), 154, $this->pdf->GetY() - 10, 4);

            $this->generateSidebar();
        }

        return $this->save($filename);
    }
}

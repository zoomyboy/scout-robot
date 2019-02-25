<?php

namespace App\Pdf\Generator;

use App\Pdf\Interfaces\LetterSidebarInterface;
use App\Pdf\Repositories\BillContentRepository;
use App\Pdf\Traits\HasDate;
use App\Pdf\Traits\HasHeader;
use App\Pdf\Traits\HasSidebar;
use App\Pdf\Traits\HasSubject;
use Carbon\Carbon;

class Bill extends GlobalPdf {

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
        $this->content = new BillContentRepository();

        $this->sidebar = $sidebar;
        $this->members = $members;
        $this->deadline = $atts['deadline'];
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

            foreach ($this->content->getPaymentsFor($member) as $key => $value) {
                $this->pdf->cell(110, 5, $this->formatStringWithEuro($key), 0, 0);
                $this->pdf->cell(0, 5, $this->formatStringWithEuro($value), 0, 1, 'R');
            }

            $this->pdf->cell(0, 1, '', 'B', 1);

            $this->pdf->SetFont('OpenSans', 'B', 10);
            $this->pdf->cell(110, 7, 'Gesamt', 0, 0);
            $this->pdf->cell(0, 7, utf8_decode($member->totalAmount([1])).' '.EURO, 0, 1, 'R');

            foreach ($this->content->getMiddleText($member, $this->deadline) as $line) {
                $line = $this->formatHtml($line);
                foreach ($line as $linePart) {
                    $this->pdf->SetFont('OpenSans', ($linePart->type == 'strong') ? 'B' : '', 10);
                    $this->pdf->Cell(0, 8, $linePart->text, 0, 1);
                }
            }

            foreach ($this->content->getBankDetails($member[0]) as $label => $content) {
                $this->pdf->Cell(60, 5, utf8_decode($label), 0, 0);
                $this->pdf->Cell(40, 5, utf8_decode($content), 0, 1, 'L');
            }

            $this->pdf->Cell(0, 5, '', 0, 1);
            $this->pdf->MultiCell(0, 5, utf8_decode('Bitte nehmen Sie zur Kenntnis, dass der für jedes Mitglied obligatorische Versicherungsschutz über die DPSG nur dann für Ihr Kind / Ihre Kinder gilt, wenn der Mitgliedsbeitrag bezahlt wurde. Wenn dies nicht geschieht, müssen wir Ihr Kind / Ihre Kinder von allen Pfadfinderaktionen ausschließen. Dazu gehören sowohl die Gruppenstunden sowie Tagesaktionen als auch mehrtägige Lager. Bei Fragen zur Rechnung können Sie mich auch persönlich erreichen unter:'));

            $this->pdf->Cell(0, 5, '', 0, 1);
            if ($this->content->getPersonName()) {
                $this->pdf->Cell(0, 5, utf8_decode('Name: '.$this->content->getPersonName()), 0, 1, 'L');
            }
            if ($this->content->getPersonPhone()) {
                $this->pdf->Cell(0, 5, utf8_decode('Tel: '.$this->content->getPersonPhone()), 0, 1, 'L');
            }
            if ($this->content->getPersonMail()) {
                $this->pdf->Cell(0, 5, utf8_decode('Mail: '.$this->content->getPersonMail()), 0, 1, 'L');
            }

            if ($this->content->getPersonName() && $this->content->getPersonFunction()) {
                $this->pdf->Cell(0, 5, '', 0, 1);
                $this->pdf->Cell(0, 5, utf8_decode('Viele Grüße'), 0, 1, 'L');
                $this->pdf->Cell(0, 5, '', 0, 1);
                $this->pdf->Cell(0, 5, utf8_decode($this->content->getPersonName()), 0, 1, 'L');
                $this->pdf->Cell(0, 5, utf8_decode($this->content->getPersonFunction()), 0, 1, 'L');
            }

            $this->pdf->Image(resource_path('img/end.png'), 154, $this->pdf->GetY() - 10, 4);

            $this->generateSidebar();
        }

        return $this->save($filename);
    }
}

<?php

namespace App\Pdf\Generator;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Interfaces\LetterSidebarInterface;
use App\Pdf\Repositories\BillContentRepository;
use App\Pdf\Repositories\BillPageRepository;
use App\Pdf\Traits\HasDate;
use App\Pdf\Traits\HasHeader;
use App\Pdf\Traits\HasSidebar;
use App\Pdf\Traits\HasSubject;
use Carbon\Carbon;

class LetterGenerator extends GlobalPdf
{
    use HasHeader;
    use HasSidebar;
    use HasSubject;
    use HasDate;

    public $members;
    public $deadline;
    public $content;
    public $sidebar;

    public function __construct($members, $atts, LetterSidebarInterface $sidebar, LetterContentInterface $content)
    {
        parent::__construct();

        $this->content = $content;
        $this->sidebar = $sidebar;
        $this->members = $members;
        $this->deadline = $atts['deadline'];
    }

    public function addPage(BillPageRepository $page)
    {
        $this->pdf->AddPage();
        $this->generateHeader($page);

        $this->generateSubject($page);
        $this->generateDate($page);

        $this->pdf->SetFont('OpenSans', '', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 10, utf8_decode($page->getGreeting()).',', 0, 1);
        $this->pdf->MultiCell(0, 5, $this->formatStringWithEuro($page->getIntro()), 0, 1);

        $this->pdf->Cell(0, 5, '', 0, 1);

        foreach ($page->getPaymentsFor() as $key => $value) {
            $this->pdf->cell(110, 5, $this->formatStringWithEuro($key), 0, 0);
            $this->pdf->cell(0, 5, $this->formatStringWithEuro($value), 0, 1, 'R');
        }

        $this->pdf->cell(0, 1, '', 'B', 1);

        $this->pdf->SetFont('OpenSans', 'B', 10);
        $this->pdf->cell(110, 7, 'Gesamt', 0, 0);
        $this->pdf->cell(0, 7, utf8_decode($page->getTotalAmount()).' '.EURO, 0, 1, 'R');

        foreach ($page->getMiddleText($page, $this->deadline) as $line) {
            $line = $this->formatHtml($line);
            foreach ($line as $linePart) {
                $this->pdf->SetFont('OpenSans', ($linePart->type == 'strong') ? 'B' : '', 10);
                $this->pdf->Write(8, $linePart->text);
            }
            $this->pdf->Ln();
        }

        foreach ($page->getBankDetails() as $label => $content) {
            $this->pdf->Cell(60, 5, utf8_decode($label), 0, 0);
            $this->pdf->Cell(40, 5, utf8_decode($content), 0, 1, 'L');
        }

        $this->pdf->Cell(0, 5, '', 0, 1);
        $this->pdf->MultiCell(0, 5, utf8_decode($this->content->getOutroText()));

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

    public function handle($filename)
    {
        foreach ($this->content->getPages() as $page) {
            $this->addPage($page);
        }

        return $this->save($filename);
    }
}

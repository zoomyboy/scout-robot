<?php

namespace App\Pdf\Generator;

use Carbon\Carbon;
use App\Pdf\Traits\HasDate;
use App\Pdf\Traits\HasHeader;
use App\Pdf\Traits\HasSidebar;
use App\Pdf\Traits\HasSubject;
use App\Pdf\Interfaces\LetterPageInterface;

class LetterGenerator extends GlobalPdf
{
    use HasHeader;
    use HasSidebar;
    use HasSubject;
    use HasDate;

    public function addPage(LetterPageInterface $page)
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

        foreach ($page->getPayments() as $key => $value) {
            $this->pdf->cell(110, 5, $this->formatStringWithEuro($key), 0, 0);
            $this->pdf->cell(0, 5, $this->formatStringWithEuro($value), 0, 1, 'R');
        }

        $this->pdf->cell(0, 1, '', 'B', 1);

        $this->pdf->SetFont('OpenSans', 'B', 10);
        $this->pdf->cell(110, 7, 'Gesamt', 0, 0);
        $this->pdf->cell(0, 7, utf8_decode($page->getTotalAmount()).' '.EURO, 0, 1, 'R');

        foreach ($page->getMiddleText() as $line) {
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
        $this->pdf->MultiCell(0, 5, utf8_decode($page->getOutroText()));

        $this->pdf->Cell(0, 5, '', 0, 1);
        if ($page->getPersonName()) {
            $this->pdf->Cell(0, 5, utf8_decode('Name: '.$page->getPersonName()), 0, 1, 'L');
        }
        if ($page->getPersonPhone()) {
            $this->pdf->Cell(0, 5, utf8_decode('Tel: '.$page->getPersonPhone()), 0, 1, 'L');
        }
        if ($page->getPersonMail()) {
            $this->pdf->Cell(0, 5, utf8_decode('Mail: '.$page->getPersonMail()), 0, 1, 'L');
        }

        if ($page->getPersonName() && $page->getPersonFunction()) {
            $this->pdf->Cell(0, 5, '', 0, 1);
            $this->pdf->Cell(0, 5, utf8_decode('Viele Grüße'), 0, 1, 'L');
            $this->pdf->Cell(0, 5, '', 0, 1);
            $this->pdf->Cell(0, 5, utf8_decode($page->getPersonName()), 0, 1, 'L');
            $this->pdf->Cell(0, 5, utf8_decode($page->getPersonFunction()), 0, 1, 'L');
        }

        $this->pdf->Image(resource_path('img/end.png'), 154, $this->pdf->GetY() - 10, 4);

        $this->generateSidebar($page);

        return $this;
    }
}

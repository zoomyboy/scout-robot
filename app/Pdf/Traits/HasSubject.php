<?php

namespace App\Pdf\Traits;

trait HasSubject
{
    public function generateSubject($page)
    {
        $this->pdf->SetFont('Arvo', '', 14);
        $this->pdf->SetTextColor(0, 48, 86);
        $this->pdf->Cell(0, 10, '', 0, 1);
        $this->pdf->Cell(60, 12, utf8_decode($page->getTitle()), 0, 0);
    }
}

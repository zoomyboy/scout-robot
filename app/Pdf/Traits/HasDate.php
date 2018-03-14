<?php

namespace App\Pdf\Traits;

trait HasDate
{
    public function generateDate($page)
    {
        $this->pdf->SetFont('OpenSans', '', 8);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 12, utf8_decode($page->getDateString()), 0, 1, 'R');
    }
}

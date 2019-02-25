<?php

namespace App\Pdf\Traits;

trait HasDate {
    public function generateDate() {   
        $this->pdf->SetFont('OpenSans', '', 8);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 12, utf8_decode($this->content->getDateString()), 0, 1, 'R');
    }
}
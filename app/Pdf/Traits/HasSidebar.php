<?php

namespace App\Pdf\Traits;

use App\Pdf\Interfaces\LetterSidebarInterface;

trait HasSidebar {
    public function generateSidebar() {
        $this->pdf->SetLeftMargin(165);
        $this->pdf->SetRightMargin(1);
        $this->pdf->SetX(0);
        $this->pdf->SetY(180);
        $this->pdf->SetFont('OpenSans', '', 8);
        $this->pdf->SetTextColor(130, 130, 130);

        foreach($this->sidebar->getContactInfo() as $line) {
            $this->pdf->Cell(0,5,utf8_decode($line), 0, 1);
        }
    }
}
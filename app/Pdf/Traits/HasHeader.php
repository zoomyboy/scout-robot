<?php

namespace App\Pdf\Traits;

trait HasHeader {
    public function generateHeader($member) {
        $filename = $this->content->getLogoFilename();

        if ($filename !== false) {
            $this->pdf->Image($filename, $this->pdf->GetPageWidth() - 45, 5, 40);
        }

        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->AddFont('OpenSans', '', 'OpenSans.php');
        $this->pdf->AddFont('OpenSans', 'B', 'OpenSans-Bold.php');
        $this->pdf->AddFont('Arvo', '', 'Arvo-Regular.php');
        $this->pdf->setFont('OpenSans', '', 8);
        $this->pdf->SetLeftMargin(20);

        $this->pdf->setX(0);
        $this->pdf->setY(44);
        $this->pdf->Cell(0, 8, utf8_decode($this->content->getFrom()), 0, 1);

        $this->pdf->setFont('OpenSans', '', 10);
        foreach($member->realAddress as $addressPart) {
            $this->pdf->Cell(0, 5, utf8_decode($addressPart), 0, 1);
        }

        $this->pdf->Image(resource_path('img/start.png'), 12, 70, 7);

        $this->pdf->setFont('OpenSans', '', 12);
        $this->pdf->SetX(165);
        $this->pdf->Cell(0, 5, utf8_decode($this->content->getGroupname()), 0, 1);

        $this->pdf->SetRightMargin(48);
    }
}
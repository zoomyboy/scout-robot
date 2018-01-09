<?php

namespace App\Services\Pdf;

use App\Conf;

define('FPDF_FONTPATH',resource_path('fonts'));
define('EURO',chr(128));

class GlobalPdf {

	public $pdf;
	public $config;

	public function __construct() {
		$this->pdf = new \FPDF;
		$this->config = Conf::first();
	}

	public function header($member) {
		if (Conf::first()->files->count()) {
			$this->pdf->Image(storage_path('app/'.$this->config->files->first()->filename), $this->pdf->GetPageWidth() - 45, 5, 40);
		}

		$this->pdf->SetTextColor(0,0,0);
		$this->pdf->AddFont('OpenSans', '', 'OpenSans.php');
		$this->pdf->AddFont('OpenSans', 'B', 'OpenSans-Bold.php');
		$this->pdf->AddFont('Arvo', '', 'Arvo-Regular.php');
		$this->pdf->setFont('OpenSans', '', 8);
		$this->pdf->SetLeftMargin(20);

		$this->pdf->setX(0);
		$this->pdf->setY(44);
		$this->pdf->Cell(0, 8, utf8_decode($this->config->letterFrom), 0, 1);

		$this->pdf->setFont('OpenSans', '', 10);
		$this->pdf->Cell(0, 5, utf8_decode('Familie '.$member->lastname), 0, 1);
		$this->pdf->Cell(0, 5, utf8_decode($member->address), 0, 1);
		$this->pdf->Cell(0, 5, utf8_decode($member->zip.' '.$member->city), 0, 1);

		$this->pdf->Image(resource_path('img/start.png'), 12, 70, 7);

		$this->pdf->setFont('OpenSans', '', 12);
		$this->pdf->SetX(165);
		$this->pdf->Cell(0, 5, utf8_decode($this->config->groupname), 0, 1);

		$this->pdf->SetRightMargin(48);
	}

	public function sidebar() {
		$this->pdf->SetLeftMargin(165);
		$this->pdf->SetRightMargin(1);
		$this->pdf->SetX(0);
		$this->pdf->SetY(180);
		$this->pdf->SetFont('OpenSans', '', 8);
		$this->pdf->SetTextColor(130, 130, 130);

		$this->pdf->Cell(0,5,utf8_decode($this->config->personName), 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->personFunction), 0, 1);
		$this->pdf->Cell(0,5,'', 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->personAddress), 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->personZip.' '.$this->config->personCity), 0, 1);
		$this->pdf->Cell(0,5,'', 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->personTel), 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->personMail), 0, 1);
		$this->pdf->Cell(0,5,utf8_decode($this->config->website), 0, 1);
	}

	public function save($filename) {
		$this->pdf->output('F', public_path('pdf/'.$filename));

		return url('pdf/'.$filename);
	}
}

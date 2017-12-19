<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \FPDF;
use App\Member;
use App\Collections\OwnCollection;

class PdfController extends Controller
{
	public function bill(Member $member) {
		$members = new OwnCollection([$member]);
		$deadline = '2017-06-07';
		return view('pdf.letter.rechnung', compact('members', 'deadline'))->withTitle('CCC');
		$pdf = \App::make('dompdf.wrapper');
		$pdf->setPaper("a4", 'portrait');
		$pdf->loadHtml($html);

		return $pdf->stream('rechnung.pdf');
    }
}

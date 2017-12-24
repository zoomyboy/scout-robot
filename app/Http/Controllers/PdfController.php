<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \FPDF;
use App\Member;
use App\Collections\OwnCollection;
use App\Services\Pdf\Bill as PdfService;

class PdfController extends Controller
{
	public function bill(Member $member) {
		$members = request()->family ? Member::family($member)->get()->groupBy('lastname') : (new OwnCollection([$member]))->groupBy('lastname');
		$service = new PdfService($members, ['deadline' => request()->deadline]);

		return $service->handle();
    }
}

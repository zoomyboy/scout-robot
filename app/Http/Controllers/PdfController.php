<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \FPDF;
use App\Member;
use App\Collections\OwnCollection;
use App\Services\Pdf\Bill as BillPdfService;
use App\Services\Pdf\Remember as RememberPdfService;

class PdfController extends Controller
{
	public function bill(Member $member) {
		$members = request()->includeFamilies === "true" ? Member::family($member)->get()->groupBy('lastname') : (new OwnCollection([$member]))->groupBy('lastname');
		$service = new BillPdfService($members, ['deadline' => request()->deadline]);

		return $service->handle();
    }

	public function remember(Member $member) {
		$members = request()->includeFamilies === "true" ? Member::family($member)->get()->groupBy('lastname') : (new OwnCollection([$member]))->groupBy('lastname');
		$service = new RememberPdfService($members, ['deadline' => request()->deadline]);

		return $service->handle();
    }
}

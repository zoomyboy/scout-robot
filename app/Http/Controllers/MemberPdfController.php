<?php

namespace App\Http\Controllers;

use App\Collections\OwnCollection;
use App\Member;
use App\Pdf\Generator\LetterGenerator;
use App\Pdf\Repositories\BillContentRepository;
use App\Pdf\Repositories\RememberContentRepository;
use App\Queries\BillPdfQuery;
use App\Queries\RememberPdfQuery;
use Illuminate\Http\Request;
use \FPDF;

class MemberPdfController extends Controller
{
    public function bill(Member $member, Request $request)
    {
        $members = request()->includeFamilies === true
            ? Member::family($member)->get()
            : (new OwnCollection([$member]))
        ;

        $service = app()->makeWith(LetterGenerator::class, [
            'members' => $members->groupBy('lastname'),
            'atts' => ['deadline' => request()->deadline],
            'content' => new BillContentRepository($members->groupBy('lastname'))
        ]);

        return $service->handle(str_slug('Rechnung für '.$member->lastname).'.pdf');
    }

    public function allBill(Member $member)
    {
        $ways = array_filter([
            request()->wayEmail == "true" ? 1 : false,
            request()->wayPost == "true" ? 2 : false
        ]);

        $groupBy = request()->includeFamilies === true
            ? function ($m) {
                return $m->lastname.$m->plz.$m->city.$m->address;
            }
        : function ($m) {
            return $m->firstname.$m->lastname.$m->plz.$m->city.$m->address;
        }
        ;

        $members = BillPdfQuery::members()->filterWays($ways)->get()->groupBy($groupBy);
        $service = app()->makeWith(LetterGenerator::class, [
            'members' => $members,
            'atts' => ['deadline' => request()->deadline],
            'content' => new BillContentRepository()
        ]);

        return $service->handle('Alle-Rechnungen.pdf');
    }


    public function remember(Member $member)
    {
        $members = request()->includeFamilies === true ? Member::family($member)->get()->groupBy('lastname') : (new OwnCollection([$member]))->groupBy('lastname');

        $service = app()->makeWith(BillPdfService::class, [
            'members' => $members,
            'atts' => ['deadline' => request()->deadline],
            'content' => new RememberContentRepository()
        ]);

        return $service->handle(str_slug('Erinnerung für '.$member->lastname).'.pdf');
    }

    public function allRemember(Member $member)
    {
        $ways = array_filter([
            request()->wayEmail == "true" ? 1 : false,
            request()->wayPost == "true" ? 2 : false
        ]);

        $groupBy = request()->includeFamilies === "true"
            ? function ($m) {
                return $m->lastname.$m->plz.$m->city.$m->address;
            }
        : function ($m) {
            return $m->firstname.$m->lastname.$m->plz.$m->city.$m->address;
        }
        ;

        $members = (new RememberPdfQuery())->handle($ways)->get()->groupBy($groupBy);
        $service = new RememberPdfService($members, ['deadline' => request()->deadline]);

        return $service->handle('Alle-Erinnerungen.pdf');
    }
}

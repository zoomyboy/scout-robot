<?php

namespace App\Http\Controllers;

use \FPDF;
use App\Member;
use Illuminate\Http\Request;
use App\Queries\BillPdfQuery;
use App\Queries\RememberPdfQuery;
use App\Collections\OwnCollection;
use App\Pdf\Generator\LetterGenerator;
use App\Pdf\Repositories\BillPageRepository;
use App\Pdf\Repositories\RememberPageRepository;
use App\Pdf\Repositories\RememberContentRepository;

class MemberPdfController extends Controller
{
    public function bill(Member $member, Request $request)
    {
        $members = request()->includeFamilies === true
            ? Member::family($member)->get()
            : (new OwnCollection([$member]))
        ;

        $content = new BillPageRepository($members, [
            'deadline' => request()->deadline
        ]);

        return app(LetterGenerator::class)
            ->addPage($content)
            ->generate('Rechnung für '.$members[0]->lastname);
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
        $members = request()->includeFamilies === true
            ? Member::family($member)->get()
            : (new OwnCollection([$member]))
        ;

        $content = new RememberPageRepository($members, [
            'deadline' => request()->deadline
        ]);

        return app(LetterGenerator::class)
            ->addPage($content)
            ->generate('Erinnerung für '.$members[0]->lastname);
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

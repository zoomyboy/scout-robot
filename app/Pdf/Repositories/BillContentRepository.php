<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use App\Traits\GeneratesBlade;
use Carbon\Carbon;

class BillContentRepository extends LetterContentRepository
{
    /**
     * Gets the title (=Subject) of the letter
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Rechnung';
    }

    /**
     * Gets the Intro text
     *
     * @return string
     */
    public function getIntro($member)
    {
        return 'Hiermit stellen wir Ihnen den aktuellen Mitgliedsbeitrag in Höhe von '.$member->totalAmount([1]).' €'.' für '.$member->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung. Dieser setzt sich wie folgt zusammen:';
    }


    /**
     * Gets the payments for the given Member
     *
     * @param Member[] $member
     */
    public function getPaymentsFor($member)
    {
        $payments = [];
        foreach ($member as $m) {
            foreach ($m->payments()->where('status_id', 1)->get() as $p) {
                $payments['Beitrag '.$p->nr.' für '.$m->firstname.' '.$m->lastname]
                    = number_format($p->subscription->amount / 100, 2, ',', '.').' €';
            }
        }

        return $payments;
    }

    /**
     * Gets text after table
     *
     * @param Member $member
     * @param date $deadline
     */
    public function getMiddleText($member, $deadline)
    {
        $deadline = $deadline
            ? Carbon::parse($deadline)->format('d.m.Y')
            : false;

        $text = [
            'Somit bitten wir Sie, den Betrag von',
            '<strong>'.$member->totalAmount([1]).' €'.'</strong>',
        ];

        if ($deadline) {
            $text[] = 'bis zum <strong>'.$deadline.'</strong> auf folgendes Konto zu überweisen:';
        } else {
            $text[] = 'auf folgendes Konto zu überweisen:';
        }

        return $text;
    }
}

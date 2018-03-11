<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use App\Traits\GeneratesBlade;
use Carbon\Carbon;

class RememberContentRepository extends LetterContentRepository
{
    /**
     * Gets the title (=Subject) of the letter
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Zahlungserinnerung';
    }

    /**
     * Gets the Intro text
     *
     * @return string
     */
    public function getIntro($member)
    {
        return 'Am Anfang des Jahres haben wir Ihnen Ihre bisherigen Ausstände in Höhe von '.$member->totalAmount([1]).' €'.' für '.$member->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung gestellt. Diese setzten sich wie folgt zusammen:';
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
            foreach ($m->payments()->where('status_id', 2)->get() as $p) {
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
            'Da von Ihnen bislang keine Zahlung eingegangen ist, erinnern wir Sie nun freundlichst daran, den Betrag in Höhe von',
            '<strong>'.$member->totalAmount([2]).' €'.'</strong>',
        ];

        if ($deadline) {
            $text[] = 'bis zum <strong>'.$deadline.'</strong> auf folgendes Konto zu überweisen:';
        } else {
            $text[] = 'auf folgendes Konto zu überweisen:';
        }

        return $text;
    }

    /**
     * Gets the total amount for member
     *
     * @return string
     */
    public function getTotalAmount($member) {
        return $member->totalAmount([2]);
    }
}

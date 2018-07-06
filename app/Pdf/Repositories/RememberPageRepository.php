<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use App\Traits\GeneratesBlade;
use Carbon\Carbon;

class RememberPageRepository extends LetterContentRepository
{
    public $members;

    /**
     * Creates a new Reposittory for a single Bill PDF Page
     *
     * @param Member[] $members
     * @return static
     */
    public static function fromMemberCollection($members)
    {
        return new static($members);
    }

    /**
     * Private constructor
     *
     * @param Member[] $members
     * @return static
     */
    private function __construct($members)
    {
        parent::__construct();

        $this->members = $members;

        return $this;
    }

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
    public function getIntro()
    {
        return 'Am Anfang des Jahres haben wir Ihnen Ihre bisherigen Ausstände in Höhe von '.$this->members->totalAmount([1]).' €'.' für '.$this->members->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung gestellt. Diese setzten sich wie folgt zusammen:';
    }

    public function getHeaderAddress()
    {
        return $this->members[0]->realAddress;
    }

    /**
     * Gets the greeting
     */
    public function getGreeting()
    {
        return 'Liebe Familie '.$this->members[0]->lastname;
    }

    /**
     * Gets the payments for the given Member
     *
     * @param Member[] $member
     */
    public function getPaymentsFor()
    {
        $payments = [];
        foreach ($this->members as $m) {
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
    public function getMiddleText($deadline)
    {
        $deadline = $deadline
            ? Carbon::parse($deadline)->format('d.m.Y')
            : false;

        $text = [
            'Da von Ihnen bislang keine Zahlung eingegangen ist, erinnern wir Sie nun freundlichst daran, den Betrag in Höhe von',
            '<strong>'.$this->members->totalAmount([2]).' €'.'</strong>',
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
    public function getTotalAmount()
    {
        return $this->members->totalAmount([1]);
    }
}

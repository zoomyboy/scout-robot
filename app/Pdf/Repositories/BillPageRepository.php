<?php

namespace App\Pdf\Repositories;

use Carbon\Carbon;

class BillPageRepository extends LetterContentRepository
{
    public $members;

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
     * Creates a new Reposittory for a single Bill PDF Page
     *
     * @param Member[] $members
     * @return static
     */
    public static function fromMemberCollection($members)
    {
        return new static($members);
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
     * Gets the Intro text
     *
     * @return string
     */
    public function getIntro()
    {
        return 'Hiermit stellen wir Ihnen den aktuellen Mitgliedsbeitrag in Höhe von '.$this->members->totalAmount([1]).' €'.' für '.$this->members->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung. Dieser setzt sich wie folgt zusammen:';
    }

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
     * Gets the payments for the given Member
     *
     * @param Member[] $member
     */
    public function getPaymentsFor()
    {
        $payments = [];
        foreach ($this->members as $m) {
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
            '<strong>'.$this->members->totalAmount([1]).' €'.'</strong>',
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

<?php

namespace App\Pdf\Repositories;

use Setting;
use Carbon\Carbon;

class RememberPageRepository extends LetterPageRepository
{
    private $members;
    private $attributes;

    /**
     * Constructor
     *
     * @param Member[] $members
     * @param Array $attributes The Attributes for the Page - e.g. the Deadline
     * @return static
     */
    public function __construct($members, $attributes)
    {
        $this->members = $members;
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the Bank details for the sidebar
     */
    public function getBankDetails()
    {
        return [
            'Kontoinhaber:' => $this->getGroupname(),
            'IBAN:' => Setting::get('letterIban'),
            'BIC:' => Setting::get('letterBic'),
            'Verwendungszweck:' => str_replace(
                '[name]',
                $this->members[0]->lastname,
                Setting::get('letterZweck')
            )
        ];
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
        return 'Am Anfang des Jahres haben wir Ihnen Ihre bisherigen Ausstände in Höhe von '.$this->members->totalAmount([1]).' €'.' für '.$this->members->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung gestellt. Diese setzten sich wie folgt zusammen:';
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
     * Gets the payments
     */
    public function getPayments()
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
    public function getMiddleText()
    {
        $deadline = $this->attributes['deadline']
            ? Carbon::parse($this->attributes['deadline'])->format('d.m.Y')
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

    /**
     * Gets the Name of the Group
     *
     * @return string
     */
    public function getGroupname()
    {
        return Setting::get('groupname');
    }

    /**
     * Gets the contact info line by line
     *
     * @return string[]
     */
    public function getContactInfo()
    {
        return [
            Setting::get('personName'),
            Setting::get('personFunction'),
            '',
            Setting::get('personAddress'),
            Setting::get('personZip').' '.Setting::get('personCity'),
            '',
            Setting::get('personTel'),
            Setting::get('personMail'),
            Setting::get('website')
        ];
    }
}

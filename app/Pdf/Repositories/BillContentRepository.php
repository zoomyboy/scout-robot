<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use App\Traits\GeneratesBlade;
use Carbon\Carbon;

class BillContentRepository implements LetterContentInterface
{

    use GeneratesBlade;

    /**
     * Constructor
     *
     * @return static
     */
    public function __construct()
    {
        $this->configModel = \App\Conf::first();
        return $this;
    }

    /**
     * @var \App\Conf $configModel The Config Eloquent Model
     */
    private $configModel;

    /**
     * Gets the Date for the document
     *
     * @return string
     */
    public function getDateString()
    {
        return $this->generateView($this->configModel->letterDate, ['date' => date('d.m.Y')]);
    }

    /**
     * Gets the From-String that will be displayed below the recipient Address
     * That way, you can see where the letter comes from.
     *
     * @return sting
     */
    public function getFrom()
    {
        return $this->configModel->letterFrom;
    }

    /**
     * Gets the Filename of the logo. A FQDN in the file system
     *
     * @return string
     */
    public function getLogoFilename()
    {
        return $this->configModel->files->count()
            ? storage_path('app/public/'.$this->configModel->files->first()->filename)
            : false;
    }

    /**
     * Gets the Bank details for the sidebar
     */
    public function getBankDetails($member)
    {
        return [
            'Kontoinhaber:' => $this->getGroupname(),
            'IBAN:' => $this->configModel->letterIban,
            'BIC:' => $this->configModel->letterBic,
            'Verwendungszweck:' => str_replace('[name]', $member->lastname, $this->configModel->letterZweck)
        ];
    }

    /**
     * Gets the Name of the responsible Person for Money
     *
     * @return string
     */
    public function getPersonName()
    {
        return $this->configModel->personName;
    }

    /**
     * Gets the E-Mail-Address of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonMail()
    {
        return $this->configModel->personMail;
    }

    /**
     * Gets the Phone of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonPhone()
    {
        return $this->configModel->personTel;
    }

    /**
     * Gets the Function of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonFunction()
    {
        return $this->configModel->personFunction;
    }

    /**
     * Gets the Name of the Group
     *
     * @return string
     */
    public function getGroupname()
    {
        return (new DefaultSidebarRepository())->getGroupname();
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
     * Gets the Intro text
     *
     * @return string
     */
    public function getIntro($member)
    {
        return 'Hiermit stellen wir Ihnen den aktuellen Mitgliedsbeitrag in Höhe von '.$member->totalAmount([1]).' €'.' für '.$member->enumNames().' für den '.$this->getGroupname().' und die DPSG in Rechnung. Dieser setzt sich wie folgt zusammen:';
    }

    /**
     * Gets the greeting
     *
     * @param Model $member
     */
    public function getGreeting($member)
    {
        return 'Liebe Familie '.$member;
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

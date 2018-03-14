<?php

namespace App\Pdf\Repositories;

use App\Conf;
use App\Pdf\Interfaces\LetterContentInterface;
use App\Traits\GeneratesBlade;

abstract class LetterContentRepository implements LetterContentInterface
{
    use GeneratesBlade;

    /**
     * @var \App\Conf $configModel The Config Eloquent Model
     */
    private $configModel;

    /**
     * Constructor
     *
     * @return static
     */
    public function __construct()
    {
        $this->configModel = Conf::first();

        return $this;
    }

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
    public function getBankDetails()
    {
        return [
            'Kontoinhaber:' => $this->getGroupname(),
            'IBAN:' => $this->configModel->letterIban,
            'BIC:' => $this->configModel->letterBic,
            'Verwendungszweck:' => str_replace('[name]', $this->members[0]->lastname, $this->configModel->letterZweck)
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
     * Gets outro below the bank details
     *
     * @return string
     */
    public function getOutroText()
    {
        return 'Bitte nehmen Sie zur Kenntnis, dass der für jedes Mitglied obligatorische Versicherungsschutz über die DPSG nur dann für Ihr Kind / Ihre Kinder gilt, wenn der Mitgliedsbeitrag bezahlt wurde. Wenn dies nicht geschieht, müssen wir Ihr Kind / Ihre Kinder von allen Pfadfinderaktionen ausschließen. Dazu gehören sowohl die Gruppenstunden sowie Tagesaktionen als auch mehrtägige Lager. Bei Fragen zur Rechnung können Sie mich auch persönlich erreichen unter:';
    }
}

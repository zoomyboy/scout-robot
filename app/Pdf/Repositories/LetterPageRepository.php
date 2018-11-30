<?php

namespace App\Pdf\Repositories;

use Setting;
use App\Traits\GeneratesBlade;
use App\Pdf\Interfaces\LetterPageInterface;

abstract class LetterPageRepository implements LetterPageInterface
{
    use GeneratesBlade;

    /**
     * Gets the Date for the document
     *
     * @return string
     */
    public function getDateString()
    {
        return $this->generateView(Setting::get('letterDate'), ['date' => date('d.m.Y')]);
    }

    /**
     * Gets the From-String that will be displayed below the recipient Address
     * That way, you can see where the letter comes from.
     *
     * @return sting
     */
    public function getFrom()
    {
        return Setting::get('letterFrom');
    }

    /**
     * Gets the Filename of the logo. A FQDN in the file system
     *
     * @return string
     */
    public function getLogoFilename()
    {
        return Setting::get('files')->count()
            ? storage_path('app/public/'.Setting::get('files')->first()->filename)
            : false;
    }


    /**
     * Gets the Name of the responsible Person for Money
     *
     * @return string
     */
    public function getPersonName()
    {
        return Setting::get('personName');
    }

    /**
     * Gets the E-Mail-Address of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonMail()
    {
        return Setting::get('personMail');
    }

    /**
     * Gets the Phone of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonPhone()
    {
        return Setting::get('personTel');
    }

    /**
     * Gets the Function of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonFunction()
    {
        return Setting::get('personFunction');
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

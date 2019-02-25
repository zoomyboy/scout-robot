<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;

class RememberContentRepository extends BillContentRepository implements LetterContentInterface {
    /**
     * Gets the title (=Subject) of the letter
     * 
     * @return string
     */
    public function getTitle() {
        return 'Zahlungserinnerung';
    }

    public function getIntro($member) {
        'Am Anfang dieses Jahres haben wir Ihnen Ihre bisherigen Ausstände von '.$member->totalAmount([2]).' € für den '.$this->getGroupname().' und die DPSG in Rechnung gestellt. Diese setzen sich wie folgt zusammen:';
    }
}

<?php

namespace App\Pdf\Interfaces;

interface LetterSidebarInterface {
    /**
     * Gets the Name of the Group
     * 
     * @return string
     */
    public function getGroupname();

    /**
     * Gets the contact info line by line
     * 
     * @return string[]
     */
    public function getContactInfo();
}
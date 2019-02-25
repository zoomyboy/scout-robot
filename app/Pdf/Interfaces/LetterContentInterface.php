<?php

namespace App\Pdf\Interfaces;

interface LetterContentInterface {
    /**
     * Gets the Date for the document
     *
     * @return string
     */
    public function getDateString();

    /**
     * Gets the Filename of the logo. A FQDN in the file system
     *
     * @return string
     */
    public function getLogoFilename();

    /**
     * Gets the From-String that will be displayed below the recipient Address
     * That way, you can see where the letter comes from.
     *
     * @return sting
     */
    public function getFrom();

    /**
     * Gets the Name of the Group for the sidebar
     *
     * @return string
     */
    public function getGroupname();

    /**
     * Gets the Bank details for the sidebar
     *
     * @param Model $member The member used as the name
     * @return array
     */
    public function getBankDetails($meber);

    /**
     * Gets the Name of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonName();

    /**
     * Gets the E-Mail-Address of the responsible Person for Money
     *
     * @return string
     */
    public function getPersonMail();

    /**
     * Gets the Phone of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonPhone();

    /**
     * Gets the Function of the responsible Person for Monay
     *
     * @return string
     */
    public function getPersonFunction();

    /**
     * Gets the title (=Subject) of the letter
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets the Intro text
     *
     * @param Model $member
     *
     * @return string
     */
    public function getIntro($member);

    /**
     * Gets the greeting
     *
     * @param Model $member
     */
    public function getGreeting($member);

    /**
     * Gets the payments for the given Member
     *
     * @param Member[] $member
     */
    public function getPaymentsFor($member);

    /**
     * Gets text after table
     *
     * @param Member $member
     * @param date $deadline
     */
    public function getMiddleText($member, $deadline);
}

<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterSidebarInterface;

class DefaultSidebarRepository implements LetterSidebarInterface
{

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
        $this->configModel = \App\Conf::first();
        return $this;
    }

    /**
     * Gets the Name of the Group
     *
     * @return string
     */
    public function getGroupname()
    {
        return $this->configModel->groupname;
    }

    /**
     * Gets the contact info line by line
     *
     * @return string[]
     */
    public function getContactInfo()
    {
        return [
            $this->configModel->personName,
            $this->configModel->personFunction,
            '',
            $this->configModel->personAddress,
            $this->configModel->personZip.' '.$this->configModel->personCity,
            '',
            $this->configModel->personPhone,
            $this->configModel->personMail,
            $this->configModel->website
        ];
    }
}

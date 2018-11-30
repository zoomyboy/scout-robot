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


}

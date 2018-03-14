<?php

namespace App\Pdf\Repositories;

use App\Pdf\Interfaces\LetterContentInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use App\Traits\GeneratesBlade;
use Carbon\Carbon;

class BillContentRepository extends LetterContentRepository
{
    private $members;

    /**
     * Constructor
     *
     * Each Element of the given collection is one single PDF Page with all
     * the members on that page.
     *
     * @param Member[][] $members The member collection
     */
    public function __construct($members)
    {
        parent::__construct();

        $this->members = $members;
    }

    public function getPages()
    {
        return $this->members->map(function ($pageMembers) {
            return BillPageRepository::fromMemberCollection($pageMembers);
        });
    }
}

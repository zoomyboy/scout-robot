<?php

namespace App\Queries;

use App\Member;
use App\Payment;

class BillPdfQuery extends PdfQuery
{
    public $statuses = [1];
}

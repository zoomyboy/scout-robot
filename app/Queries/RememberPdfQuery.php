<?php

namespace App\Queries;

use App\Member;
use App\Payment;

class RememberPdfQuery extends PdfQuery {
    public $statuses = [2];
}

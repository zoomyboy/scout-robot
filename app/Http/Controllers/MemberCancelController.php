<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;
use App\Jobs\CancelNamiMember;

class MemberCancelController extends Controller
{
    public function index(Member $member) {
        dispatch(new CancelNamiMember($member));
    }
}

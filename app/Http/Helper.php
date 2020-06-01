<?php

use App\DocumentType;
use App\Organisation;
use App\RegularDocument;
use App\Setting;
use App\Signup;
use App\SuperadminSetting;
use App\User;
use App\Workshop;
use Illuminate\Support\Facades\Auth;


function getBranchUuid()
{
    $incNumber = 001;
    $res = \App\Model\Branch::orderBy('id', 'DESC')->first();

    if ($res) {
        $incNumber = $res->uuid + 1;
    }
    return $incNumber;
}

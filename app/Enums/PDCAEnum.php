<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum PDCAEnum : string
{
    //
    use EnumTrait;
    case NOTYET = 'not_yet';
    case PLAN = 'plan';
    case DO = 'do';
    case CHECK = 'check';
    case ACT = 'act';
}

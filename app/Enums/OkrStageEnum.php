<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OkrStageEnum:string
{
    use EnumTrait;
    case NOTYET = 'not_yet';
    case PLAN = 'plan';
    case DO = 'do';
    case DONE= 'done';
    case CHECK = 'check';
    case COMPLETED= 'completed';
    case ACT = 'act';
}

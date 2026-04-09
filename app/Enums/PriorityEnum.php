<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum PriorityEnum :string
{
    //
    use EnumTrait;
    case URGENT_IMPORTANT = 'urgent_important';
    case NOT_URGENT_IMPORTANT = 'not_urgent_important';
    case URGENT_NOT_IMPORTANT = 'urgent_not_important';
    case NOT_URGENT_NOT_IMPORTANT = 'not_urgent_not_important';
}


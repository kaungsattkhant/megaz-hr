<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ContractTypeEnum : string
{
    //
    use EnumTrait;
    case ORIENTATION = 'orientation';
    case OCCASIONAL = 'occasional';
}

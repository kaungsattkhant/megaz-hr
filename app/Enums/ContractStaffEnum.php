<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ContractStaffEnum :string
{
    //
    //
    use EnumTrait;
    case DRAFT = 'draft';
    case SIGNED = 'signed';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}

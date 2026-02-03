<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum StaffStatus: string{
    use EnumTrait;
    // Before interview
    case APPLIED     = 'applied';
    case SHORTLISTED = 'shortlisted';
    case INTERVIEWED = 'interviewed';

        // After interview
    case HIRED       = 'hired';
    case REJECTED    = 'rejected';

        // Employment types
    // case PROBATION   = 'probation';  
    case PERMANENT   = 'permanent';
    case TEMPORARY   = 'temporary';
    // case CONTRACT    = 'contract';
}

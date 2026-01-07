<?php

namespace App\Enums;

enum OffDayRequestType: string
{
    case OffDay = 'off_day';
    case ShiftChange = 'shift_change';
}

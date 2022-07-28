<?php

namespace App\Entities;

use App\Interfaces\Enum;

class NegotiationPeriodType extends Enum
{
    const DAY = 'day';
    const HOUR = 'hour';
    const MONTH = 'month';
}

?>

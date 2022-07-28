<?php

namespace App\Entities;

use App\Interfaces\Enum;

class CreditType extends Enum
{
    const WITHDRAW = 'withdraw';
    const CHARGE = 'CHARGE';
    const BUY = 'buy';
    const SELL = 'sell';
}

?>

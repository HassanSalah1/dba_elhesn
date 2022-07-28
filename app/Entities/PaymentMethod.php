<?php

namespace App\Entities;

use App\Interfaces\Enum;

class PaymentMethod extends Enum
{
    const ONLINE_PAYMENT = 1;
    const BANK_TRANSFER = 2;
    const WALLET = 3;

    public static function chargeWallet()
    {
        return [self::ONLINE_PAYMENT, self::BANK_TRANSFER];
    }
}

?>

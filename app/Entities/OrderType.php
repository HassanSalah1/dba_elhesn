<?php

namespace App\Entities;

use App\Interfaces\Enum;

class OrderType extends Enum
{
    const DIRECT = 'direct';
    const DAMAIN = 'damain';
    const NEGOTIATION = 'negotiation';
    const BID = 'bid';

}

?>

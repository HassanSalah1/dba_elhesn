<?php

namespace App\Entities;

use App\Interfaces\Enum;

class OrderUserType extends Enum
{
    const BUYER = 'buyer';
    const SELLER = 'seller';
}

?>

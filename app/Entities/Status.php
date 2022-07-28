<?php

namespace App\Entities;

use App\Interfaces\Enum;

class Status extends Enum
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const UNVERIFIED = 2;
}

?>

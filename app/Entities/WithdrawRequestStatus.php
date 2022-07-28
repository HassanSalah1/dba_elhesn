<?php

namespace App\Entities;

use App\Interfaces\Enum;

class WithdrawRequestStatus extends Enum
{
    const WAIT = 'wait';
    const APPROVED = 'approved';
    const REFUSED = 'refused';
}

?>

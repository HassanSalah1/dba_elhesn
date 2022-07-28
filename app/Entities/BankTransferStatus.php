<?php

namespace App\Entities;

use App\Interfaces\Enum;

class BankTransferStatus extends Enum
{
    const WAIT = 'wait';
    const APPROVED = 'approved';
    const REFUSED = 'refused';
}

?>

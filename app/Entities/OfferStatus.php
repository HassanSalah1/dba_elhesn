<?php

namespace App\Entities;

use App\Interfaces\Enum;

class OfferStatus extends Enum
{
    const NEW = 'new';
    const APPROVED = 'approved';
    const REFUSED = 'refused';
}

?>

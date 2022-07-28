<?php

namespace App\Entities;

use App\Interfaces\Enum;

class NotificationType extends Enum
{
    const TEXT = 'text';
    const ORDER = 'order';
    const CREDIT = 'credit';

}

?>

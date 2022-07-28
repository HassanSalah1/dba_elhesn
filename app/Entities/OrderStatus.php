<?php

namespace App\Entities;

use App\Interfaces\Enum;

class OrderStatus extends Enum
{
    const WAIT = 'wait';
    const ACCEPTED = 'accepted';
    const REFUSED = 'refused';
    const EDITED = 'edited';
    const CANCELLED = 'canceled';
    const PAYMENT_APPROVED = 'payment_approved';
    const PROGRESS = 'progress';
    const SHIPPED = 'shipped';
    const COMPLETED = 'completed';
    const RECEIVE_REFUSED = 'receive_refused';
    const RECEIVE_REFUSED_APPROVED = 'receive_refused_approved';
}

?>

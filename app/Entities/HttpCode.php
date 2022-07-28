<?php

namespace App\Entities;

use App\Interfaces\Enum;

class HttpCode extends Enum
{

    const SUCCESS = 200;
    const AUTH_ERROR = 401;
    const ERROR = 404;
    const NOT_VERIFIED = 403;
    const CHANGE_CART = 402;

}

?>

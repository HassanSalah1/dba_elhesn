<?php

namespace App\Entities;

use App\Interfaces\Enum;

class UserRoles extends Enum
{
    const ADMIN = 'admin';
    const EMPLOYEE = 'employee';
    const CUSTOMER = 'customer';
    const REGISTER = 'register';

    public  static function getUserKeys()
    {
        return [self::CUSTOMER];
    }
}

?>

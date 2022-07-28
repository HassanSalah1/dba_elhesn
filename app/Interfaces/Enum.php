<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 4/17/2019
 * Time: 11:06 AM
 */

namespace App\Interfaces;

use ReflectionClass;

abstract class Enum
{
    static function getKeys()
    {
        $class = new ReflectionClass(get_called_class());
        return array_values($class->getConstants());
    }

    static function getArray()
    {
        $class = new ReflectionClass(get_called_class());
        return $class->getConstants();
    }
}
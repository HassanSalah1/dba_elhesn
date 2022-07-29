<?php

namespace App\Entities;

use App\Interfaces\Enum;

class ImageType extends Enum
{

    const NEWS = 'news';
    const ACTION = 'action';
    const CITY_DESCRIPTION = 'city_description';
    const COMMITTEE = 'committee';
}

?>

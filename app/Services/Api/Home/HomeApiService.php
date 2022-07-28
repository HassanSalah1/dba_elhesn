<?php

namespace App\Services\Api\Home;

use App\Repositories\Api\Home\HomeApiRepository;
use App\Repositories\General\UtilsRepository;

class HomeApiService
{

    public static function getHome(array $data)
    {
        $response = HomeApiRepository::getHome($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

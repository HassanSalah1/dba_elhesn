<?php

namespace App\Services\Api\Order;

use App\Repositories\Api\Order\OrderSettingApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class OrderSettingApiService
{

    public static function getShipments(array $data)
    {
        $response = OrderSettingApiRepository::getShipments($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getPaymentMethods(array $data)
    {
        $response = OrderSettingApiRepository::getPaymentMethods($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getBankAccounts(array $data)
    {
        $response = OrderSettingApiRepository::getBankAccounts($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

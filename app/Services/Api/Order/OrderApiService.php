<?php

namespace App\Services\Api\Order;

use App\Repositories\Api\Order\OrderApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class OrderApiService
{

    public static function getMyOrders(array $data)
    {
        $response = OrderApiRepository::getMyOrders($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addDirectOrder(array $data)
    {
        $keys = [
            'product_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderApiRepository::addDirectOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getOrderDetails(array $data)
    {
        $response = OrderApiRepository::getOrderDetails($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getMyBidOrders(array $data)
    {
        $response = OrderApiRepository::getMyBidOrders($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

<?php
namespace App\Services\Dashboard\Order;

use App\Repositories\Dashboard\Order\OrderRepository;
use App\Repositories\General\UtilsRepository;

class OrderService
{

    public static function getOrdersData(array $data)
    {
        return OrderRepository::getOrdersData($data);
    }

    public static function approveOrderRefuseRequest(array $data)
    {
        $response =  OrderRepository::approveOrderRefuseRequest($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function refuseOrderRefuseRequest(array $data)
    {
        $response =  OrderRepository::refuseOrderRefuseRequest($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

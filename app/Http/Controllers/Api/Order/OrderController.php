<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Api\Order\OrderApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function getMyOrders(Request $request)
    {
        $data = $request->all();
        return OrderApiService::getMyOrders($data);
    }

    public function getMyBidOrders(Request $request)
    {
        $data = $request->all();
        return OrderApiService::getMyBidOrders($data);
    }

    public function addDirectOrder(Request $request){
        $data = $request->all();
        return OrderApiService::addDirectOrder($data);
    }

    public function getOrderDetails($id , Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        return OrderApiService::getOrderDetails($data);
    }

}

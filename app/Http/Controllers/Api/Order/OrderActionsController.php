<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Api\Order\OrderActionsApiService;
use Illuminate\Http\Request;

class OrderActionsController extends Controller
{

    public function acceptOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::acceptOrder($data);
    }

    public function refuseOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::refuseOrder($data);
    }

    public function cancelOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::cancelOrder($data);
    }

    public function payOrder(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['platform'] = 'api';
        return OrderActionsApiService::payOrder($data);
    }


    public function makeOrderShipped(Request $request)
    {
        $data = $request->all();
        $data['platform'] = 'api';
        return OrderActionsApiService::makeOrderShipped($data);
    }

    public function acceptOrderDelivery(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::acceptOrderDelivery($data);
    }


    public function refuseOrderDelivery(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::refuseOrderDelivery($data);
    }

}

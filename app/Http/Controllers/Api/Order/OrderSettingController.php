<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Api\Order\OrderSettingApiService;
use Illuminate\Http\Request;

class OrderSettingController extends Controller
{

    public function getShipments(Request $request)
    {
        $data = $request->all();
        return OrderSettingApiService::getShipments($data);
    }


    public function getPaymentMethods(Request $request)
    {
        $data = $request->all();
        return OrderSettingApiService::getPaymentMethods($data);
    }

    public function getBankAccounts(Request $request)
    {
        $data = $request->all();
        return OrderSettingApiService::getBankAccounts($data);
    }

}

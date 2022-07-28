<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Api\User\CreditApiService;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    //

    public function getMyWallet(Request $request)
    {
        $data = $request->all();
        return CreditApiService::getMyWallet($data);
    }

    public function chargeMyWallet(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['platform'] = 'api';
        return CreditApiService::chargeMyWallet($data);
    }

    public function requestWithdraw(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CreditApiService::requestWithdraw($data);
    }


    public function payDone(Request $request)
    {
        $data = $request->all();
        return CreditApiService::payDone($data);
    }

    public function payError(Request $request)
    {
        return response()->json([], 400);
    }

}

?>

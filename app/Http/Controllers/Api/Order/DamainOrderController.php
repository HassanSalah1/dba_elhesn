<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Api\Order\DamainOrderApiService;
use Illuminate\Http\Request;

class DamainOrderController extends Controller
{

    public function addDamainOrder(Request $request)
    {
        $data = $request->all();
        return DamainOrderApiService::addDamainOrder($data);
    }


    public function addProductToDamainOrder(Request $request)
    {
        $data = $request->all();
        return DamainOrderApiService::addProductToDamainOrder($data);
    }


    public function editDamainOrderProduct(Request $request)
    {
        $data = $request->all();
        return DamainOrderApiService::editDamainOrderProduct($data);
    }


    public function getDamainOrderDetails(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return DamainOrderApiService::getDamainOrderDetails($data);
    }


}

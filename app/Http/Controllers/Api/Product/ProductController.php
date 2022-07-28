<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Services\Api\Product\ProductApiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function getNegotiationPercent(Request $request)
    {
        $data = $request->all();
        return ProductApiService::getNegotiationPercent($data);
    }

    public function getNegotiationPeriod(Request $request)
    {
        $data = $request->all();
        return ProductApiService::getNegotiationPeriod($data);
    }


    public function uploadProductImage(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ProductApiService::uploadProductImage($data);
    }

    public function removeProductImage(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return ProductApiService::removeProductImage($data);
    }

    public function addProduct(Request $request)
    {
        $data = $request->all();
        return ProductApiService::addProduct($data);
    }


    public function getProductDetails(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return ProductApiService::getProductDetails($data);
    }

    public function editProduct(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return ProductApiService::editProduct($data);
    }

    public function deleteProduct(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return ProductApiService::deleteProduct($data);
    }

    public function addProductComment(Request $request)
    {
        $data = $request->all();
        return ProductApiService::addProductComment($data);
    }

}

?>

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function getProfile(Request $request)
    {
        $data = $request->all();
        return UserApiService::getProfile($data);
    }

    public function editProfile(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['user'] = auth()->user();
        return UserApiService::editProfile($data);
    }

    public function getMyProducts(Request $request)
    {
        $data = $request->all();
        return UserApiService::getMyProducts($data);
    }


    public function toggleFavouriteProduct(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return UserApiService::toggleFavouriteProduct($data);
    }

    public function getMyFavouriteProducts(Request $request)
    {
        $data = $request->all();
        return UserApiService::getMyFavouriteProducts($data);
    }

    public function getMyNotifications(Request $request)
    {
        $data = $request->all();
        return UserApiService::getMyNotifications($data);
    }

    public function getMyChats(Request $request)
    {
        $data = $request->all();
        return UserApiService::getMyChats($data);
    }


    public function getChatDetails($id,Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        return UserApiService::getChatDetails($data);
    }

}

?>

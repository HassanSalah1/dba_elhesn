<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Services\Api\Product\ProductApiService;
use App\Services\Api\Setting\SettingApiService;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function getAbout(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getAbout($data);
    }

    public function getTerms(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getTerms($data);
    }

    public function getPrivacy(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getPrivacy($data);
    }

    public function getFaqs(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getFaqs($data);
    }

    public function getUserGuides(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getUserGuides($data);
    }

    public function getIntros(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getIntros($data);
    }

    public function getContactTypes(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getContactTypes($data);
    }

    public function getCountries(Request $request){
        $data = $request->all();
        return SettingApiService::getCountries($data);
    }


    public function getCities($id , Request $request){
        $data = $request->all();
        $data['country_id'] = $id;
        return SettingApiService::getCities($data);
    }

    public function addContact(Request $request){
        $data = $request->all();
        return SettingApiService::addContact($data);
    }


    public function uploadGeneralImage(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return SettingApiService::uploadGeneralImage($data);
    }

    public function removeGeneralImage(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return SettingApiService::removeGeneralImage($data);
    }

}

?>

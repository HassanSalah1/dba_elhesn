<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Services\Api\Product\ProductApiService;
use App\Services\Api\Setting\SettingApiService;
use Illuminate\Http\Request;

class SettingController extends Controller
{


    public function getTerms(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getTerms($data);
    }

    public function getContactDetails(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getContactDetails($data);
    }

    public function addContact(Request $request)
    {
        $data = $request->all();
        return SettingApiService::addContact($data);
    }

    public function getTeams(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getTeams($data);
    }

    public function getGallery(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getGallery($data);
    }


    public function getNews(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getNews($data);
    }

    public function getActions(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getActions($data);
    }
}

?>

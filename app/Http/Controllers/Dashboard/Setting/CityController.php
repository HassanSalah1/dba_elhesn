<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CityController extends Controller
{
    //
    public function showCities()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.cities_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.country_name'), trans('admin.actions'));
        return view('admin.settings.city')->with($data);
    }

    public function getCitiesData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return CityService::getCitiesData($data);
    }


    public function deleteCity(Request $request)
    {
        $data = $request->all();
        return CityService::deleteCity($data);
    }

    public function restoreCity(Request $request)
    {
        $data = $request->all();
        return CityService::restoreCity($data);
    }

}

<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\CountryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CountryController extends Controller
{
    //
    public function showCountries()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.countries_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.phonecode'), trans('admin.flag'), trans('admin.actions'));
        return view('admin.settings.country')->with($data);
    }

    public function getCountriesData(Request $request)
    {
        $data = $request->all();
        return CountryService::getCountriesData($data);
    }

    public function changeCountry(Request $request)
    {
        $data = $request->all();
        return CountryService::changeCountry($data);
    }

}

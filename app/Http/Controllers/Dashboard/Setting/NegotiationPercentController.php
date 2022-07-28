<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\NegotiationPercentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class NegotiationPercentController extends Controller
{
    //
    public function showNegotiationPercents()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.negotiation_percents_title');
        $data['debatable_names'] = array(trans('admin.percent'),
            trans('admin.actions'));
        return view('admin.settings.negotiation_percent')->with($data);
    }

    public function getNegotiationPercentsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return NegotiationPercentService::getNegotiationPercentsData($data);
    }

    public function addNegotiationPercent(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return NegotiationPercentService::addNegotiationPercent($data);
    }

    public function deleteNegotiationPercent(Request $request)
    {
        $data = $request->all();
        return NegotiationPercentService::deleteNegotiationPercent($data);
    }

    public function getNegotiationPercentData(Request $request)
    {
        $data = $request->all();
        return NegotiationPercentService::getNegotiationPercentData($data);
    }

    public function editNegotiationPercent(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return NegotiationPercentService::editNegotiationPercent($data);
    }

}

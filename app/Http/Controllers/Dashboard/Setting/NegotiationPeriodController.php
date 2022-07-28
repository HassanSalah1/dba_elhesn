<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\NegotiationPeriodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class NegotiationPeriodController extends Controller
{
    //
    public function showNegotiationPeriods()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.negotiation_periods_title');
        $data['debatable_names'] = array(trans('admin.period'), trans('admin.type'),
            trans('admin.actions'));
        return view('admin.settings.negotiation_period')->with($data);
    }

    public function getNegotiationPeriodsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return NegotiationPeriodService::getNegotiationPeriodsData($data);
    }

    public function addNegotiationPeriod(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return NegotiationPeriodService::addNegotiationPeriod($data);
    }

    public function deleteNegotiationPeriod(Request $request)
    {
        $data = $request->all();
        return NegotiationPeriodService::deleteNegotiationPeriod($data);
    }

    public function getNegotiationPeriodData(Request $request)
    {
        $data = $request->all();
        return NegotiationPeriodService::getNegotiationPeriodData($data);
    }

    public function editNegotiationPeriod(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return NegotiationPeriodService::editNegotiationPeriod($data);
    }

}

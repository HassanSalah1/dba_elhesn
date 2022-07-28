<?php

namespace App\Http\Controllers\Dashboard\Order\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Order\Setting\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PaymentMethodController extends Controller
{
    //
    public function showPaymentMethods()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.payment_methods_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.actions'));
        return view('admin.order.settings.payment_method')->with($data);
    }

    public function getPaymentMethodsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return PaymentMethodService::getPaymentMethodsData($data);
    }

    public function deletePaymentMethod(Request $request)
    {
        $data = $request->all();
        return PaymentMethodService::deletePaymentMethod($data);
    }

    public function restorePaymentMethod(Request $request)
    {
        $data = $request->all();
        return PaymentMethodService::restorePaymentMethod($data);
    }

}

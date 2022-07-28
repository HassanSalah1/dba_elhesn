<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\FaqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FaqController extends Controller
{
    //
    public function showFaqs()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.faqs_title');
        $data['debatable_names'] = array(trans('admin.question'), trans('admin.answer'),
            trans('admin.actions'));
        return view('admin.settings.faq')->with($data);
    }

    public function getFaqsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return FaqService::getFaqsData($data);
    }

    public function addFaq(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return FaqService::addFaq($data);
    }

    public function deleteFaq(Request $request)
    {
        $data = $request->all();
        return FaqService::deleteFaq($data);
    }

    public function getFaqData(Request $request)
    {
        $data = $request->all();
        return FaqService::getFaqData($data);
    }

    public function editFaq(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return FaqService::editFaq($data);
    }

}

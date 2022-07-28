<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\IntroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IntroController extends Controller
{
    //
    public function showIntros()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.intros_title');
        $data['debatable_names'] = array(trans('admin.title'), trans('admin.description'),
            trans('admin.actions'));
        return view('admin.settings.intro')->with($data);
    }

    public function getIntrosData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return IntroService::getIntrosData($data);
    }

    public function addIntro(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return IntroService::addIntro($data);
    }

    public function deleteIntro(Request $request)
    {
        $data = $request->all();
        return IntroService::deleteIntro($data);
    }

    public function getIntroData(Request $request)
    {
        $data = $request->all();
        return IntroService::getIntroData($data);
    }

    public function editIntro(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return IntroService::editIntro($data);
    }

}

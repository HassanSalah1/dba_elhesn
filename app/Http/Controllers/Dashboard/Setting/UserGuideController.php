<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\UserGuideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserGuideController extends Controller
{
    //
    public function showUserGuides()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.user_guide_title');
        $data['debatable_names'] = array(trans('admin.image'), trans('admin.description'),
            trans('admin.actions'));
        return view('admin.settings.user_guide')->with($data);
    }

    public function getUserGuidesData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return UserGuideService::getUserGuidesData($data);
    }

    public function addUserGuide(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return UserGuideService::addUserGuide($data);
    }

    public function deleteUserGuide(Request $request)
    {
        $data = $request->all();
        return UserGuideService::deleteUserGuide($data);
    }

    public function getUserGuideData(Request $request)
    {
        $data = $request->all();
        return UserGuideService::getUserGuideData($data);
    }

    public function editUserGuide(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return UserGuideService::editUserGuide($data);
    }

}

<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{

    public function showProfile(Request $request)
    {
        $data['title'] = trans('site.profile_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['social'] = SettingApiRepository::loadSocial();
        $data['cities'] = SettingApiRepository::getCities([
            'country_id' => 178
        ])['data'];
        return view('site.user.profile')->with($data);
    }

    public function editProfile(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['user'] = auth()->user();
        return UserApiService::editProfile($data);
    }


}

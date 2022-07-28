<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use App\Services\Api\Setting\SettingApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PageSiteController extends Controller
{

    // questions
    public function showQuestions(Request $request)
    {
        $data['title'] = trans('site.questions_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        return view('site.pages.questions')->with($data);
    }

    //  about
    public function showAbout(Request $request)
    {
        $data['title'] = trans('site.about_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['about'] = SettingApiRepository::getAbout([])['data']['about'];
        return view('site.pages.about')->with($data);
    }

    //  policy
    public function showPolicy(Request $request)
    {
        $data['title'] = trans('site.policy_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['privacy'] = SettingApiRepository::getPrivacy([])['data']['privacy'];
        return view('site.pages.policy')->with($data);
    }

    //  terms
    public function showTerms(Request $request)
    {
        $data['title'] = trans('site.terms_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['terms'] = SettingApiRepository::getTerms([])['data']['terms'];
        return view('site.pages.terms')->with($data);
    }

    //  faq
    public function showFaq(Request $request)
    {
        $data['title'] = trans('site.faq_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['faqs'] = SettingApiRepository::getFaqs([])['data'];
        return view('site.pages.faq')->with($data);
    }

    //  faq
    public function showGuide(Request $request)
    {
        $data['title'] = trans('site.guide_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['guides'] = SettingApiRepository::getUserGuides([])['data'];
        return view('site.pages.guide')->with($data);
    }


    //  contact us
    public function showContact(Request $request)
    {
        $data['title'] = trans('site.contactus_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $settingData = SettingApiRepository::getContactTypes([])['data'];
        $data['social'] = $settingData['social'];
        $data['contactTypes'] = $settingData['contactTypes'];
        return view('site.pages.contact')->with($data);
    }

    // processContact
    public function processContact(Request $request)
    {
        $data = $request->all();
        return SettingApiService::addContact($data);
    }
}

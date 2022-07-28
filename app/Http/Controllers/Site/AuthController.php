<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Services\Api\Auth\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function showLogin()
    {
        $data['title'] = trans('site.login_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        return view('site.auth.login')->with($data);
    }

    public function processAuthLogin(Request $request)
    {
        $data = $request->all();
        $data['web'] = 1;
        return AuthApiService::login($data);
    }

    public function showRegister()
    {
        $data['title'] = trans('site.register_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        $data['cities'] = SettingApiRepository::getCities([
            'country_id' => 178
        ])['data'];
        return view('site.auth.register')->with($data);
    }

    public function processAuthRegister(Request $request)
    {
        $data = $request->all();
        $data['web'] = 1;
        return AuthApiService::signup($data);
    }

    public function showVerify()
    {
        $data['title'] = trans('site.verify_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        return view('site.auth.verify')->with($data);
    }

    public function processAuthVerify(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        if ($user) {
            $data['phonecode'] = $user->phonecode;
            $data['phone'] = $user->phone;
        }
        return AuthApiService::checkVerificationCode($data);
    }


    public function showForgetPassword()
    {
        $data['title'] = trans('site.forget_password?');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        return view('site.auth.forget_password')->with($data);
    }

    public function processForgetPassword(Request $request)
    {
        $data = $request->all();
        $data['web'] = 1;
        return AuthApiService::forgetPassword($data);
    }

    public function showForgetVerify($id)
    {

        $phone = Crypt::decrypt($id);
        $user = User::where(['phone' => $phone])->first();
        if (!$user) {
            return redirect()->to(url('/'));
        }

        $data['title'] = trans('site.forget_verify_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        $data['id'] = $id;
        return view('site.auth.forget_verify')->with($data);
    }

    public function processForgetVerify(Request $request)
    {
        $data = $request->all();
        if (isset($data['key'])) {
            $data['phone'] = Crypt::decrypt($data['key']);
        }
        $data['web'] = 1;
        return AuthApiService::checkVerificationCode($data);
    }


    public function showChangePassword($id)
    {

        $phone = Crypt::decrypt($id);
        $user = User::where(['phone' => $phone])->first();
        if (!$user || !Session::has('dm_chpss') || Session::get('dm_chpss') !== $id) {
            return redirect()->to(url('/'));
        }
        Session::remove('dm_chpss');
        $data['title'] = trans('site.change_password_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['className'] = 'auth';
        $data['id'] = $id;
        return view('site.auth.change_password')->with($data);
    }


    public function processChangePassword(Request $request)
    {
        $data = $request->all();
        if (isset($data['key'])) {
            $data['phone'] = Crypt::decrypt($data['key']);
        }
        $data['web'] = 1;
        return AuthApiService::changeForgetPassword($data);
    }

}

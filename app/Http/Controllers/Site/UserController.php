<?php

namespace App\Http\Controllers\Site;

use App\Entities\NegotiationPeriodType;
use App\Entities\ProductType;
use App\Http\Controllers\Controller;
use App\Models\NegotiationPeriod;
use App\Models\Product;
use App\Repositories\Api\Auth\AuthApiRepository;
use App\Repositories\Api\Order\OrderSettingApiRepository;
use App\Repositories\Api\Product\CategoryApiRepository;
use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\CreditApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use App\Services\Api\User\CreditApiService;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{

    public function showMyProducts(Request $request)
    {
        $data['title'] = trans('site.my_products');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $filter = $request->all();
        $filter['web'] = 1;
        $filter['request'] = $request;
        $data['products'] = UserApiRepository::getMyProducts($filter)['data'];
        $data['activeClass'] = 'my_products';
        return view('site.user.my-products')->with($data);
    }

    public function showMyFavourites(Request $request)
    {
        $data['title'] = trans('site.my_favourites');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $filter = $request->all();
        $filter['web'] = 1;
        $filter['request'] = $request;
        $data['products'] = UserApiRepository::getMyFavouriteProducts($filter)['data'];
        return view('site.user.my-favourites')->with($data);
    }

    public function showMyWallet(Request $request)
    {
        $data['title'] = trans('site.wallet');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $filter = $request->all();
        $filter['web'] = 1;
        $filter['request'] = $request;

        $data['wallet'] = CreditApiRepository::getMyWallet($filter)['data'];
        $data['bankAccounts'] = OrderSettingApiRepository::getBankAccounts($filter)['data'];
        return view('site.user.wallet')->with($data);
    }

    public function requestWithdraw(Request $request)
    {
        $data = $request->all();
        return CreditApiService::requestWithdraw($data);
    }

    public function chargeMyWallet(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CreditApiService::chargeMyWallet($data);
    }

    public function logout(Request $request)
    {
        AuthApiRepository::logout();
        return redirect()->to(url('/login'));
    }

}

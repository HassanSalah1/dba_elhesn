<?php

namespace App\Http\Controllers\Site;

use App\Entities\Key;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Repositories\Api\Home\HomeApiRepository;
use App\Repositories\Api\Product\CategoryApiRepository;
use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeSiteController extends Controller
{

    //  home
    public function showHome(Request $request)
    {
        $data['title'] = trans('site.home_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['categories'] = CategoryApiRepository::getCategories([])['data'];
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['small_about'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::SMALL_ABOUT_EN : Key::SMALL_ABOUT_AR])->first();

        $data['direct'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::DIRECT_EN : Key::DIRECT_AR])->first();
        $data['bid'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::BID_EN : Key::BID_AR])->first();
        $data['damain'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::DAMAIN_EN : Key::DAMAIN_AR])->first();
        $data['negotiation'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::NEGOTIATION_EN : Key::NEGOTIATION_AR])->first();
        $data['download'] = Setting::where(['key' => $data['locale'] == 'en' ?
            Key::DOWNLOAD_EN : Key::DOWNLOAD_AR])->first();

        $data['activeClass'] = 'home';

        return view('site.home')->with($data);
    }


//  home
    public function showCategoryProducts($id, Request $request)
    {
        $category = Category::withoutTrashed()->where(['id' => $id, 'category_id' => null])->first();
        if (!$category) {
            return redirect()->to(url('/'));
        }
        $data['title'] = $category->name;
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
        $filter['category_id'] = $category->id;
        $filter['web'] = 1;
        $filter['request'] = $request;
        $data['category'] = $category;
        $data['products'] = HomeApiRepository::getHome($filter)['data'];
        return view('site.product.product')->with($data);
    }

    public function showProductDetails($id, Request $request)
    {
        $filter = ['id' => $id, 'web' => 1, 'request' => $request];
        $product = ProductApiRepository::getProductDetails($filter);
        if (!isset($product['data'])) {
            return redirect()->to(url('/'));
        }
        $product = $product['data'];
        $data['title'] = $product['name'];
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['product'] = $product;

        return view('site.product.product-details')->with($data);
    }


}

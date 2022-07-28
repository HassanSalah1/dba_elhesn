<?php

namespace App\Http\Controllers\Site;

use App\Entities\NegotiationPeriodType;
use App\Entities\ProductType;
use App\Http\Controllers\Controller;
use App\Models\NegotiationPeriod;
use App\Models\Product;
use App\Repositories\Api\Product\CategoryApiRepository;
use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use App\Services\Api\Product\ProductApiService;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{

    public function showAddProduct(Request $request)
    {
        $data['title'] = trans('site.add_product');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['categories'] = CategoryApiRepository::getCategories([])['data'];
        $data['type'] = $request->type ?: ProductType::DIRECT;
        if ($data['type'] == ProductType::DIRECT) {
            $data['percents'] = ProductApiRepository::getNegotiationPercent([])['data'];
        } else {
            $data['periods'] = ProductApiRepository::getNegotiationPeriod([])['data'];
        }
        return view('site.product.add_product')->with($data);
    }

    public function saveAddProduct(Request $request)
    {
        $data = $request->all();
        $data['web'] = 1;
        $data['request'] = $request;
        return ProductApiService::addProduct($data);
    }

    public function showEditProduct($id, Request $request)
    {
        $user = auth()->user();
        $product = Product::withoutTrashed()->where(['user_id' => $user->id, 'id' => $id])->first();
        if (!$product) {
            return redirect()->to(url('/'));
        }
        $data['title'] = trans('site.edit_product');
        $data['locale'] = App::getLocale();

        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['categories'] = CategoryApiRepository::getCategories([])['data'];
        $data['type'] = $product->type;
        if ($data['type'] == ProductType::DIRECT) {
            $data['percents'] = ProductApiRepository::getNegotiationPercent([])['data'];
        } else {
            $data['periods'] = ProductApiRepository::getNegotiationPeriod([])['data'];
        }

        $data['sub_categories'] = CategoryApiRepository::getCategories(['category_id' => $product->category_id])['data'];
        $data['sub_sub_categories'] = CategoryApiRepository::getCategories(['category_id' => $product->sub_category_id])['data'];

        $data['product'] = $product;

        return view('site.product.edit_product')->with($data);
    }

    public function removeProductImage(Request $request , $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return ProductApiService::removeProductImage($data);
    }

    public function saveEditProduct(Request $request)
    {
        $data = $request->all();
        $data['web'] = 1;
        $data['request'] = $request;
        return ProductApiService::editProduct($data);
    }

    public function favouriteProduct(Request $request)
    {
        $data = $request->all();
        return UserApiService::toggleFavouriteProduct($data);
    }

}

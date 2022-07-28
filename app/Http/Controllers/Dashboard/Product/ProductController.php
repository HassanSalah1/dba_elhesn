<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Dashboard\Product\ProductRepository;
use App\Services\Dashboard\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    //
    public function showProducts()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.products_title');
        $data['debatable_names'] = array('#',trans('admin.name'), trans('admin.user_name'),
            trans('admin.category_name'), trans('admin.type'),
            trans('admin.image'), trans('admin.actions'));
        return view('admin.product.product')->with($data);
    }

    public function getProductsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return ProductService::getProductsData($data);
    }

    public function getProductsDetails($id ,Request $request)
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $product = Product::find($id);
        if(!$product){
            return redirect()->to(url('/admin/products'));
        }
        $data['title'] = $product->name;
        $product->image = $product->image();
        $data['product'] = $product;
        return view('admin.product.product_details')->with($data);
    }

}

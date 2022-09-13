<?php
namespace App\Repositories\Dashboard\News;

use App\Entities\ProductType;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class ProductRepository
{

    // get Products and create datatable data.
    public static function getProductsData(array $data)
    {
        $products = Product::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($products)
            ->editColumn('image', function ($product) {
                $image = $product->image();
                if ($image && file_exists($image->image)) {
                    return '<a href="' . url($image->image) . '" data-popup="lightbox">
                                <img src="' . url($image->image) . '" class="img-rounded img-preview"
                                style="max-height:50px;max-width:50px;"></a>';
                }
            })
            ->editColumn('type', function ($product) {
                return trans('api.' . (($product->type === ProductType::DIRECT && $product->negotiation === 1) ? 'negotiation_type' : $product->type));
            })
            ->addColumn('username', function ($product) {
                return @$product->user->name;
            })
            ->addColumn('category_name', function ($product) {
                $category = $product->category;
                $sub_category = $product->sub_category;
                $sub_sub_category = $product->sub_sub_category;
                $html = '';
                if ($category) {
                    $html .= $category->name;
                }
                if ($sub_category) {
                    $html .= ' - ' . $sub_category->name;
                }
                if ($sub_sub_category) {
                    $html .= ' - ' . $sub_sub_category->name;
                }
                return $html;
            })
            ->addColumn('actions', function ($order) {
                $ul = '';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.details_action') . '" id="' . $order->id . '" href="' . url('/admin/product/details/' . $order->id) . '" class="on-default edit-row btn btn-info"><i data-feather="eye"></i></a>
                   ';
                return $ul;
            })
            ->make(true);
    }

}

?>

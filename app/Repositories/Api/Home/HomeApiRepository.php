<?php

namespace App\Repositories\Api\Home;

use App\Entities\HttpCode;
use App\Entities\OrderStatus;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupplierCategory;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class HomeApiRepository
{

    // get Home
    public static function getHome(array $data)
    {
        $web = isset($data['web']) ? 1 : 0;
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);
        $search = [
            ['user_id', '!=', $web ? auth()->id() : auth()->guard('api')->id()],
        ];
        if (isset($data['type'])) {
            $search['type'] = $data['type'];
        }


        $products = Product::withoutTrashed()
            ->where(function ($query) {
                $query->where(function ($query2) {
                    $query2->doesntHave('normalOrder');
                });
                $query->orWhere(function ($query2) {
                    $query2->whereNotIn('id' , function ($query3){
                        $query3->select('product_id')
                            ->from(with('normal_orders'))
                            ->whereIn('order_id' , function ($query4) {
                                $query4->select('id')
                                    ->from(with(new Order())->getTable())
                                    ->whereIn('status', [OrderStatus::WAIT, OrderStatus::RECEIVE_REFUSED,
                                        OrderStatus::COMPLETED, OrderStatus::SHIPPED, OrderStatus::PROGRESS,
                                        OrderStatus::PAYMENT_APPROVED, OrderStatus::ACCEPTED]);
                            });
                    });
                });
            })
            ->where($search)
            ->where(function ($query) use ($data) {
                if (isset($data['city_id'])) {
                    $query->whereIn('user_id', function ($query) use ($data) {
                        $query->select('id')
                            ->from(with(new User())->getTable())
                            ->where(['city_id' => $data['city_id']]);
                    });
                }

                if (isset($data['category_id'])) {
                    $query->where(function ($query2) use ($data) {
                        $query2->where('category_id', $data['category_id']);
                        $query2->orWhere('sub_category_id', $data['category_id']);
                        $query2->orWhere('sub_sub_category_id', $data['category_id']);
                    });
                }
            })
            ->orderBy('id', 'desc');

        if($web){
            $products = ProductResource::collection($products->get())->toArray($data['request']);
        }else{
            $count = $products->count();
            $products = $products->offset($offset)
                ->skip($offset)
                ->take($per_page)
                ->get();

            $products = new Paginator(ProductResource::collection($products), $count, $per_page);
        }
        return [
            'data' => $products,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

}

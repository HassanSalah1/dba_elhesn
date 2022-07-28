<?php

namespace App\Repositories\Api\Order;

use App\Entities\HttpCode;
use App\Entities\NotificationType;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\ProductType;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderOffersDetailsResource;
use App\Http\Resources\OrderResource;
use App\Jobs\SendNotificationFCMJob;
use App\Models\NormalOrder;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;

class OrderApiRepository
{

    // get My Orders
    public static function getMyOrders(array $data)
    {
        $web = (isset($data['web'])) ? 1 : 0;
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $statuses = [];
        if (!isset($data['status']) || $data['status'] === 'new') { // new
            $statuses = [OrderStatus::WAIT, OrderStatus::ACCEPTED, OrderStatus::REFUSED,
                OrderStatus::EDITED, OrderStatus::PAYMENT_APPROVED, OrderStatus::PROGRESS];
        } else if ($data['status'] === 'progress') { // progress
            $statuses = [OrderStatus::RECEIVE_REFUSED, OrderStatus::SHIPPED];
        } else if ($data['status'] === 'completed') { // completed
            $statuses = [OrderStatus::COMPLETED];
        } else if ($data['status'] === 'cancelled') { // cancelled
            $statuses = [OrderStatus::CANCELLED, OrderStatus::RECEIVE_REFUSED_APPROVED];
        }

        $orders = Order::whereIn('status', $statuses)
            ->whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT, OrderType::NEGOTIATION])
            ->where(function ($query) use ($data) {
                $user = auth()->user();
                if (!isset($data['type']) || $data['type'] === 'all') {
                    $query->where(['user_id' => $user->id]);
                    $query->orWhere(['other_user_id' => $user->id]);
                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['user_id' => 0, 'user_type' => OrderUserType::BUYER, 'type' => OrderType::NEGOTIATION]);
                        $query2->wherehas('offers', function ($query) use ($user) {
                            $query->where(['user_id' => $user->id]);
                        });
                    });
                } else if ($data['type'] === OrderUserType::BUYER) {
                    $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    });

                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['user_id' => 0, 'user_type' => OrderUserType::BUYER, 'type' => OrderType::NEGOTIATION]);
                        $query2->wherehas('offers', function ($query) use ($user) {
                            $query->where(['user_id' => $user->id]);
                        });
                    });
                } else if ($data['type'] === OrderUserType::SELLER) {
                    $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    });
                }
            })
            ->orderBy('id', 'DESC');

        if ($web) {
            $orders = OrderResource::collection($orders->get())->toArray($data['request']);
        } else {

            $count = $orders->count();
            $orders = $orders->offset($offset)
                ->skip($offset)
                ->take($per_page)
                ->get();

            $orders = new Paginator(OrderResource::collection($orders), $count, $per_page);
        }
        return [
            'data' => $orders,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function addDirectOrder(array $data)
    {
        $user = auth()->user();
        $product = Product::withoutTrashed()
            ->where(function ($query) {
                $query->where(function ($query2) {
                    $query2->doesntHave('normalOrder');
                });
                $query->orWhere(function ($query2) {
                    $query2->whereNotIn('id', function ($query3) {
                        $query3->select('product_id')
                            ->from(with('normal_orders'))
                            ->whereIn('order_id', function ($query4) {
                                $query4->select('id')
                                    ->from(with(new Order())->getTable())
                                    ->whereIn('status', [OrderStatus::WAIT, OrderStatus::RECEIVE_REFUSED,
                                        OrderStatus::COMPLETED, OrderStatus::SHIPPED, OrderStatus::PROGRESS,
                                        OrderStatus::PAYMENT_APPROVED, OrderStatus::ACCEPTED]);
                            });
                    });
                });
            })
            ->where([
                'id' => $data['product_id'],
                ['user_id', '!=', $user->id]
            ])->first();

        if ($product) {
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'type' => OrderType::DIRECT,
                    'status' => OrderStatus::WAIT,
                    'user_id' => $user->id,
                    'user_type' => OrderUserType::BUYER,
                    'other_user_id' => $product->user_id,
                ]);

                $price = $product->price;
                if ($product->type === ProductType::BID) {
                    $offer = Offer::where(['product_id' => $product->id])
                        ->select('price', 'status', 'id')
                        ->orderBy('price', 'DESC')->first();
                    $price = $offer ? $offer->price : ($product->max_price ? $product->max_price : $product->price);
                }

                NormalOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'fields' => (isset($data['fields'])) ? json_encode($data['fields']) : null,
                    'price' => $price
                ]);
                DB::commit();
                // send notifications
                $user = $order->other_user;
                $notification_obj = [
                    'title_key' => 'notification_new_direct_order_title',
                    'message_key' => 'notification_new_direct_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];
                $extraData = [];
                $extraData['product_name'] = $order->normalOrder->product->name;
                SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);

                // return success response
                return [
                    'data' => OrderDetailsResource::make($order),
                    'message' => trans('api.done_successfully'),
                    'code' => HttpCode::SUCCESS
                ];
            } catch (\Exception $ex) {
                DB::rollBack();
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function getOrderDetails(array $data)
    {
        $order = Order::where([
            'id' => $data['id'],
            ['type', '!=', OrderType::DAMAIN]
        ])->where(function ($query) {
            $user = auth()->user();
            $query->where(['user_id' => $user->id]);
            $query->orWhere(['other_user_id' => $user->id]);
            $query->orWhere(function ($query2) use ($user) {
                $query2->where(['user_id' => 0, 'user_type' => OrderUserType::BUYER]);
                $query2->wherehas('offers', function ($query) use ($user) {
                    $query->where(['user_id' => $user->id]);
                });
            });
        })->first();
        if ($order) {
            $data = $order->type === OrderType::DIRECT ?
                OrderDetailsResource::make($order) : OrderOffersDetailsResource::make($order);
            return [
                'data' => $data,
                'message' => 'success',
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function getMyBidOrders(array $data)
    {
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $statuses = [];
        if (!isset($data['status']) || $data['status'] === 'new') { // new
            $statuses = [OrderStatus::WAIT, OrderStatus::ACCEPTED, OrderStatus::REFUSED,
                OrderStatus::EDITED, OrderStatus::PAYMENT_APPROVED, OrderStatus::PROGRESS];
        } else if ($data['status'] === 'progress') { // progress
            $statuses = [OrderStatus::RECEIVE_REFUSED, OrderStatus::SHIPPED];
        } else if ($data['status'] === 'completed') { // completed
            $statuses = [OrderStatus::COMPLETED];
        } else if ($data['status'] === 'cancelled') { // cancelled
            $statuses = [OrderStatus::CANCELLED, OrderStatus::RECEIVE_REFUSED_APPROVED];
        }

        $orders = Order::whereIn('status', $statuses)
            ->whereIn('type', [OrderType::BID])
            ->where(function ($query) use ($data) {
                $user = auth()->user();
                if (!isset($data['type']) || $data['type'] === 'all') {
                    $query->where(['user_id' => $user->id]);
                    $query->orWhere(['other_user_id' => $user->id]);

                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['user_id' => 0, 'user_type' => OrderUserType::BUYER]);
                        $query2->wherehas('offers', function ($query) use ($user) {
                            $query->where(['user_id' => $user->id]);
                        });
                    });
                } else if ($data['type'] === OrderUserType::BUYER) {
                    $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    });

                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['user_id' => 0, 'user_type' => OrderUserType::BUYER]);
                        $query2->wherehas('offers', function ($query) use ($user) {
                            $query->where(['user_id' => $user->id]);
                        });
                    });
                } else if ($data['type'] === OrderUserType::SELLER) {
                    $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    $query->orWhere(function ($query2) use ($user) {
                        $query2->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    });
                }
            })
            ->orderBy('id', 'DESC');

        $count = $orders->count();
        $orders = $orders->offset($offset)
            ->skip($offset)
            ->take($per_page)
            ->get();

        $products = new Paginator(OrderResource::collection($orders), $count, $per_page);
        return [
            'data' => $products,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

}

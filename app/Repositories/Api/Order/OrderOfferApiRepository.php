<?php

namespace App\Repositories\Api\Order;

use App\Entities\HttpCode;
use App\Entities\Key;
use App\Entities\NotificationType;
use App\Entities\OfferStatus;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\ProductType;
use App\Http\Resources\OrderOffersDetailsResource;
use App\Jobs\SendNotificationFCMJob;
use App\Models\NormalOrder;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class OrderOfferApiRepository
{

    public static function addProductNegotiationOffer(array $data)
    {
        $user = auth()->user();
        $product = Product::withoutTrashed()
            ->where([
                'id' => $data['product_id'],
                'negotiation' => 1,
                ['user_id', '!=', $user->id]
            ])->first();

        if ($product) {
            $order = Order::whereHas('normalOrder', function ($query) use ($product) {
                $query->where(['product_id' => $product->id]);
            })
                ->where(['type' => OrderType::DIRECT])
                ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::RECEIVE_REFUSED_APPROVED])
                ->first();
            if (!$order) {
                $price = $product->price;
                $percent = ($price * $product->percent / 100);
                $lowest_price = $price - $percent;

                $offer = Offer::where(['product_id' => $product->id])
                    ->orderBy('price', 'DESC')->first();
                if ($offer) {
                    $max_price = $offer->price;
                    if ($max_price >= $data['price']) {
                        return [
                            'message' => trans('api.price_nogotiation_error_message'),
                            'code' => HttpCode::ERROR
                        ];
                    }
                }

                $approvedOffer = Offer::where([
                    'product_id' => $product->id,
                    'status' => OfferStatus::APPROVED
                ])->first();

                $userOffer = Offer::where([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'status' => OfferStatus::NEW
                ])->first();

                if ($approvedOffer || $userOffer) {
                    return [
                        'message' => trans('api.cannot_make_offer_error_message'),
                        'code' => HttpCode::ERROR
                    ];
                }
                $offer = Offer::where(['product_id' => $product->id])->first();
                if ($data['price'] >= $lowest_price) {
                    DB::beginTransaction();
                    try {
                        if (!$offer) {
                            $order = Order::create([
                                'type' => OrderType::NEGOTIATION,
                                'status' => OrderStatus::WAIT,
                                'user_id' => 0,
                                'user_type' => OrderUserType::BUYER,
                                'other_user_id' => $product->user_id,
                            ]);
                        } else {
                            $order = $offer->order;
                            if (in_array($order->status, [OrderStatus::PROGRESS, OrderStatus::SHIPPED,
                                OrderStatus::COMPLETED, OrderStatus::RECEIVE_REFUSED])) {
                                return [
                                    'message' => trans('api.general_error_message'),
                                    'code' => HttpCode::ERROR
                                ];
                            }
                        }

                        $offer = Offer::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'user_id' => $user->id,
                            'fields' => (isset($data['fields'])) ? json_encode($data['fields']) : null,
                            'price' => $data['price'],
                            'status' => OfferStatus::NEW
                        ]);

                        if ($order->status === OrderStatus::CANCELLED) {
                            $order->update([
                                'status' => OrderStatus::WAIT
                            ]);
                        }

                        $user = $order->other_user;
                        $notification_obj = [
                            'title_key' => 'notification_new_direct_order_title',
                            'message_key' => 'notification_new_direct_order_message',
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'offer_id' => $offer->id,
                            'type' => NotificationType::ORDER
                        ];
                        $extraData = [];
                        $extraData['product_name'] = $offer->product->name;
                        SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);
                        DB::commit();
                        // return success response
                        return [
                            'data' => OrderOffersDetailsResource::make($order),
                            'message' => trans('api.done_successfully'),
                            'code' => HttpCode::SUCCESS
                        ];
                    } catch (\Exception $ex) {
                        DB::rollBack();
                    }
                } else {
                    return [
                        'message' => trans('api.negotiation_placeholder', ['price' => $percent]),
                        'code' => HttpCode::ERROR
                    ];
                }
            }
        }
        return [
            'message' => trans('api.general_error_messages'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function acceptNegotiationOffer(array $data)
    {
        $offer = Offer::where(['id' => $data['offer_id'], 'status' => OfferStatus::NEW])->first();
        if ($offer) {
            $order = $offer->order;
            if ($order && $order->status === OrderStatus::WAIT) {
                DB::beginTransaction();
                try {
                    $offer->update([
                        'status' => OfferStatus::APPROVED
                    ]);
                    $order->update([
                        'user_id' => $offer->user_id,
                        'status' => OrderStatus::ACCEPTED
                    ]);
                    NormalOrder::create([
                        'order_id' => $order->id,
                        'product_id' => $offer->product_id,
                        'fields' => $offer->fields,
                        'price' => $offer->price
                    ]);
                    DB::commit();

                    $user = $offer->user;
                    $notification_obj = [
                        'title_key' => 'notification_accept_offer_title',
                        'message_key' => 'notification_accept_offer_message',
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => NotificationType::ORDER
                    ];
                    $extraData = [];
                    $extraData['product_name'] = $offer->product->name;
                    $extraData['days'] = Setting::where(['key' => Key::MAX_TIME_TO_PAY])->first()->value;
                    SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);

                    return [
                        'data' => OrderOffersDetailsResource::make($order),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                } catch (\Exception $ex) {
                    DB::rollBack();
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function refuseNegotiationOffer(array $data)
    {
        $offer = Offer::where(['id' => $data['offer_id'], 'status' => OfferStatus::NEW])->first();
        if ($offer) {
            $order = $offer->order;
            if ($order && $order->status === OrderStatus::WAIT) {
                DB::beginTransaction();
                try {
                    $offer->update([
                        'status' => OfferStatus::REFUSED
                    ]);

                    $offersCount = $order->offers()->where(function ($query) {
                        $query->where(['status' => OfferStatus::APPROVED]);
                        $query->orWhere(['status' => OfferStatus::NEW]);
                    })->count();
                    if ($offersCount === 0) {
                        $order->update(['status' => OrderStatus::CANCELLED]);
                    }
                    DB::commit();
                    return [
                        'data' => OrderOffersDetailsResource::make($order),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                } catch (\Exception $ex) {
                    DB::rollBack();
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function addProductBidOffer(array $data)
    {
        $user = auth()->user();
        $product = Product::withoutTrashed()
            ->where([
                'id' => $data['product_id'],
                'type' => ProductType::BID,
                ['user_id', '!=', $user->id]
            ])->first();
        if ($product) {
            $endDate = date_create(date('Y-m-d h:i:s a', strtotime("+" . $product->period . " " . $product->period_type,
                strtotime($product->created_at))));
            $createDate = date_create(date('Y-m-d h:i:s a'));
            if ($endDate > $createDate) {
                $order = Order::whereHas('normalOrder', function ($query) use ($product) {
                    $query->where(['product_id' => $product->id]);
                })
                    ->where(function ($query) {
                        $query->where(['type' => OrderType::BID]);
                        $query->orWhere(['type' => OrderType::DIRECT]);
                    })
                    ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::RECEIVE_REFUSED_APPROVED])
                    ->first();

                if (!$order) {
                    $offer = Offer::where(['product_id' => $product->id])
                        ->orderBy('price', 'DESC')->first();
                    $max_price = $offer ? $offer->price : $product->price;
                    if (($offer && $max_price >= $data['price']) || (!$offer && $max_price > $data['price'])) {
                        return [
                            'message' => trans('api.price_bid_error_message'),
                            'code' => HttpCode::ERROR
                        ];
                    }

                    $approvedOffer = Offer::where([
                        'product_id' => $product->id,
                        'status' => OfferStatus::APPROVED
                    ])->first();

                    $userOffer = Offer::where([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'status' => OfferStatus::NEW
                    ])->first();

                    if ($approvedOffer || $userOffer) {
                        return [
                            'message' => trans('api.cannot_make_offer_error_message'),
                            'code' => HttpCode::ERROR
                        ];
                    }

                    ///
                    DB::beginTransaction();
                    try {
                        if (!$offer) {
                            $order = Order::create([
                                'type' => OrderType::BID,
                                'status' => OrderStatus::WAIT,
                                'user_id' => 0,
                                'user_type' => OrderUserType::BUYER,
                                'other_user_id' => $product->user_id,
                            ]);
                        } else {
                            $order = $offer->order;
                            if (in_array($order->status, [OrderStatus::PROGRESS, OrderStatus::SHIPPED,
                                OrderStatus::COMPLETED, OrderStatus::RECEIVE_REFUSED])) {
                                return [
                                    'message' => trans('api.general_error_message'),
                                    'code' => HttpCode::ERROR
                                ];
                            }
                        }

                        $offer = Offer::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'user_id' => $user->id,
                            'fields' => (isset($data['fields'])) ? json_encode($data['fields']) : null,
                            'price' => $data['price'],
                            'status' => OfferStatus::NEW
                        ]);
                        if ($order->status === OrderStatus::CANCELLED) {
                            $order->update([
                                'status' => OrderStatus::WAIT
                            ]);
                        }

                        // send notification to buyer
                        $user = $order->other_user;
                        $notification_obj = [
                            'title_key' => 'notification_new_bid_title',
                            'message_key' => 'notification_new_bid_message',
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'offer_id' => $offer->id,
                            'type' => NotificationType::ORDER
                        ];
                        $extraData = [];
                        $extraData['product_name'] = $offer->product->name;
                        $extraData['user_name'] = $offer->user->name;
                        SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);
                        DB::commit();
                        // return success response
                        return [
                            'data' => OrderOffersDetailsResource::make($order),
                            'message' => trans('api.done_successfully'),
                            'code' => HttpCode::SUCCESS
                        ];
                    } catch (\Exception $ex) {
                        DB::rollBack();
                    }
                    ///
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function acceptBidOffer(array $data)
    {
        $offer = Offer::where(['id' => $data['offer_id'], 'status' => OfferStatus::NEW])->first();
        if ($offer) {
            $order = $offer->order;
            if ($order && $order->status === OrderStatus::WAIT) {
                DB::beginTransaction();
                try {
                    $offer->update([
                        'status' => OfferStatus::APPROVED
                    ]);
                    $order->update([
                        'user_id' => $offer->user_id,
                        'status' => OrderStatus::ACCEPTED
                    ]);
                    NormalOrder::create([
                        'order_id' => $order->id,
                        'product_id' => $offer->product_id,
                        'fields' => $offer->fields,
                        'price' => $offer->price
                    ]);
                    DB::commit();
                    return [
                        'data' => OrderOffersDetailsResource::make($order),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                } catch (\Exception $ex) {
                    DB::rollBack();
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function refuseBidOffer(array $data)
    {
        $offer = Offer::where(['id' => $data['offer_id'], 'status' => OfferStatus::NEW])->first();
        if ($offer) {
            $order = $offer->order;
            if ($order && $order->status === OrderStatus::WAIT) {
                DB::beginTransaction();
                try {
                    $offer->update([
                        'status' => OfferStatus::REFUSED
                    ]);
                    $offersCount = $order->offers()->where(function ($query) {
                        $query->where(['status' => OfferStatus::APPROVED]);
                        $query->orWhere(['status' => OfferStatus::NEW]);
                    })->count();
                    if ($offersCount === 0) {
                        $order->update(['status' => OrderStatus::CANCELLED]);
                    }
                    DB::commit();
                    return [
                        'data' => OrderOffersDetailsResource::make($order),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                } catch (\Exception $ex) {
                    DB::rollBack();
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

}

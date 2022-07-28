<?php

namespace App\Repositories\Api\Order;

use App\Entities\BankTransferStatus;
use App\Entities\CreditType;
use App\Entities\HttpCode;
use App\Entities\NotificationType;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\PaymentMethod;
use App\Entities\ShipmentType;
use App\Jobs\SendNotificationFCMJob;
use App\Models\BankTransfer;
use App\Models\Credit;
use App\Models\Image;
use App\Models\Order;
use App\Models\RefuseReason;
use App\Models\Shipment;
use App\Repositories\General\Arbpg;
use App\Repositories\General\UtilsRepository;
use Illuminate\Support\Facades\DB;

class OrderActionsApiRepository
{

    public static function acceptOrder(array $data)
    {
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_accept']) && $actions['can_accept']) {
                $order->update([
                    'status' => OrderStatus::ACCEPTED
                ]);

                // TODO: send notification with new status
                $user = auth()->user();
                if ($order->type === OrderType::DAMAIN) {
                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::SELLER)
                        ? $order->other_user : $order->user;
                } else {
                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::BUYER)
                        ? $order->other_user : $order->user;
                }
                $notification_obj = [
                    'title_key' => $order->type === OrderType::DAMAIN ?
                        'notification_accept_damain_order_title' : 'notification_accept_order_title',
                    'message_key' => $order->type === OrderType::DAMAIN ?
                        'notification_accept_damain_order_message' : 'notification_accept_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];

                SendNotificationFCMJob::dispatch($user, $notification_obj, []);

                return [
                    'data' => $order->actions(),
                    'message' => trans('api.done_successfully'),
                    'code' => HttpCode::SUCCESS
                ];
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function refuseOrder(array $data)
    {
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_refuse']) && $actions['can_refuse']) {
                $order->update([
                    'status' => $order->type === OrderType::DAMAIN ?
                        OrderStatus::REFUSED : OrderStatus::CANCELLED,
                    'reason' => isset($data['reason']) ? $data['reason'] : null
                ]);
                //
                // TODO: send notification with new status
                $user = auth()->user();
                if ($order->type === OrderType::DAMAIN) {
                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::SELLER)
                        ? $order->other_user : $order->user;
                } else {
                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::BUYER)
                        ? $order->other_user : $order->user;
                }
                $notification_obj = [
                    'title_key' => $order->type === OrderType::DAMAIN ?
                        'notification_refuse_damain_order_title' : 'notification_refuse_order_title',
                    'message_key' => $order->type === OrderType::DAMAIN ?
                        'notification_refuse_damain_order_message' : 'notification_refuse_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];

                $extraData = [];
                if (isset($data['reason'])) {
                    $extraData['reason'] = $data['reason'];
                }
                SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);

                return [
                    'data' => $order->actions(),
                    'message' => trans('api.done_successfully'),
                    'code' => HttpCode::SUCCESS
                ];
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function cancelOrder(array $data)
    {
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_cancelled']) && $actions['can_cancelled']) {
                $order->update([
                    'status' => OrderStatus::CANCELLED,
                    'reason' => isset($data['reason']) ? $data['reason'] : null
                ]);
                //
                // TODO: send notification with new status

                $user = auth()->user();
                $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::BUYER)
                    ? $order->other_user : $order->user;
                $notification_obj = [
                    'title_key' => 'notification_cancel_damain_order_title',
                    'message_key' => 'notification_cancel_damain_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];

                $extraData = [];
                if (isset($data['reason'])) {
                    $extraData['reason'] = $data['reason'];
                }
                SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);


                return [
                    'data' => $order->actions(),
                    'message' => trans('api.done_successfully'),
                    'code' => HttpCode::SUCCESS
                ];
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function payOrder(array $data)
    {
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $user = auth()->user();
            $actions = $order->actions();
            if (isset($actions['can_payment']) && $actions['can_payment']) {
                $done = false;
                $totalOrder = $order->total_price;
                // Bank transfer
                if ($data['payment_method_id'] == PaymentMethod::BANK_TRANSFER) {
                    $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                    $image_name = 'image';
                    $image_path = 'uploads/bankTransfers/';
                    $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
                    if ($image !== false) {
                        BankTransfer::create([
                            'order_id' => $order->id,
                            'bank_id' => $data['bank_id'],
                            'image' => $image,
                            'status' => BankTransferStatus::WAIT
                        ]);
                        $order->update([
                            'payment_method_id' => $data['payment_method_id'],
                            'status' => OrderStatus::PAYMENT_APPROVED
                        ]);
                        $done = true;
                    }
                }
                else if ($data['payment_method_id'] == PaymentMethod::ONLINE_PAYMENT) {
                    // TODO:: handle online payment
                    $card_number = $data['card_number'];
                    $expiry_month = $data['expiry_month'];
                    $expiry_year = $data['expiry_year'];
                    $cvv = $data['cvv'];
                    $card_holder = $data['holder_name'];
                    $arbPg = new Arbpg();
                    $apiUrl = '/api/v1/order/payment-done';
                    $webUrl = '/order/payment-done';
                    $response = $arbPg->getmerchanthostedPaymentid($card_number,
                        $expiry_month, $expiry_year, $cvv, $card_holder,
                        $order->id, $totalOrder, OrderStatus::PROGRESS, $apiUrl, $webUrl, $data['platform'], 'pay');
                    if ($response['status'] == 200) {
                        return [
                            'data' => ['url' => $response['url']],
                            'message' => trans('api.done_successfully'),
                            'code' => HttpCode::SUCCESS
                        ];
                    }else{
                        return [
                            'data' => $response,
                            'message' => trans('api.done_successfully'),
                            'code' => HttpCode::ERROR
                        ];
                    }
//                    $done = true;
//                    $order->update([
//                        'status' => OrderStatus::PROGRESS
//                    ]);
                } else if ($data['payment_method_id'] == PaymentMethod::WALLET) { // wallet
                    if ($user->real_balance >= $totalOrder) {
                        $credit = Credit::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'amount' => $totalOrder,
                            'type' => CreditType::BUY
                        ]);
                        if ($credit) {
                            $order->update([
                                'status' => OrderStatus::PROGRESS
                            ]);
                            $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::SELLER)
                                ? $order->other_user : $order->user;
                            $notification_obj = [
                                'title_key' => 'notification_payed_order_title',
                                'message_key' => 'notification_payed_order_message',
                                'user_id' => $user->id,
                                'order_id' => $order->id,
                                'type' => NotificationType::ORDER
                            ];
                            $extraData = [];
                            if ($order->type === OrderType::DAMAIN) {
                                $extraData['product_name'] = $order->damainOrder->name;
                            } else {
                                $extraData['product_name'] = $order->normalOrder->product->name;
                            }

                            SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);
                            $done = true;
                        }
                    } else {
                        return [
                            'message' => trans('api.no_enough_balance'),
                            'code' => HttpCode::ERROR
                        ];
                    }
                }
                if ($done) {
                    return [
                        'data' => $order->actions(),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function makeOrderShipped(array $data)
    {
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_shipment']) && $actions['can_shipment']) {
                // TODO: handle payment cycle
                $user = auth()->user();
                if ($data['shipment_type'] == ShipmentType::APP_SHIP) {
                    $card_number = $data['card_number'];
                    $expiry_month = $data['expiry_month'];
                    $expiry_year = $data['expiry_year'];
                    $cvv = $data['cvv'];
                    $card_holder = $data['holder_name'];
                    $arbPg = new Arbpg();
                    $apiUrl = '/api/v1/order/payment-done';
                    $webUrl = '/order/payment-done';
                    $shipment = Shipment::find($data['shipment_id']);
                    $response = $arbPg->getmerchanthostedPaymentid($card_number,
                        $expiry_month, $expiry_year, $cvv, $card_holder,
                        $order->id, $shipment->price, $shipment->id, $apiUrl, $webUrl, $data['platform'], 'shipment', $user->id);
                    if ($response['status'] == 200) {
                        return [
                            'message' => trans('api.done_successfully'),
                            'data' => [
                                'url' => $response['url'],
                            ],
                            'code' => HttpCode::SUCCESS
                        ];
                    } else {
                        return [
                            'message' => trans('api.general_error_message'),
                            'code' => HttpCode::ERROR
                        ];
                    }
                }
                DB::beginTransaction();
                try {
                    $shipment_code = null;
                    if ($data['shipment_type'] == ShipmentType::SELLER_SHIP) {
                        $shipment_code = self::createUniqueShippmentCode($order);
                    }
                    $order->update([
                        'shipment_id' => isset($data['shipment_id']) && $data['shipment_type'] == ShipmentType::APP_SHIP
                            ? $data['shipment_id'] : null,
                        'shipment_type' => $data['shipment_type'],
                        'shipment_code' => $shipment_code,
                        'status' => OrderStatus::SHIPPED
                    ]);
                    DB::commit();

                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::BUYER)
                        ? $order->other_user : $order->user;
                    $notification_obj = [
                        'title_key' => 'notification_shipped_order_title',
                        'message_key' => 'notification_shipped_order_message',
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => NotificationType::ORDER
                    ];
                    $extraData = [];
                    if ($order->type === OrderType::DAMAIN) {
                        $extraData['product_name'] = $order->damainOrder->name;
                    } else {
                        $extraData['product_name'] = $order->normalOrder->product->name;
                    }

                    SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);
                    return [
                        'data' => $order->actions(),
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

    public static function createUniqueShippmentCode($order)
    {
        $code = UtilsRepository::createVerificationCode($order->id, 4);
        $isExist = Order::where(['shipment_code' => $code])->first();
        if ($isExist) {
            self::createUniqueShippmentCode($order);
        }
        return $code;
    }

    public static function acceptOrderDelivery(array $data)
    {
        $user = auth()->user();
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_accept_delivery']) && $actions['can_accept_delivery']) {
                if (($order->shipment_type === ShipmentType::SELLER_SHIP && isset($data['code'])
                        && $order->shipment_code === $data['code']) || ($order->shipment_type === ShipmentType::APP_SHIP)) {
                    $orderData = [
                        'status' => OrderStatus::COMPLETED
                    ];
                    $order->update($orderData);
                    // TODO: add balance to seller
                    $seller = ($user->id === $order->user_id && $order->user_type === OrderUserType::SELLER)
                        ? $order->user_id : $order->other_user_id;
                    Credit::create([
                        'user_id' => $seller,
                        'order_id' => $order->id,
                        'amount' => $order->price,
                        'type' => CreditType::SELL
                    ]);
                    // TODO: send notification

                    $user = auth()->user();
                    $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::SELLER)
                        ? $order->other_user : $order->user;
                    $notification_obj = [
                        'title_key' => 'notification_completed_order_title',
                        'message_key' => 'notification_completed_order_message',
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => NotificationType::ORDER
                    ];
                    $extraData = [];
                    if ($order->type === OrderType::DAMAIN) {
                        $extraData['product_name'] = $order->damainOrder->name;
                    } else {
                        $extraData['product_name'] = $order->normalOrder->product->name;
                    }
                    SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);

                    return [
                        'data' => $order->actions(),
                        'message' => trans('api.done_successfully'),
                        'code' => HttpCode::SUCCESS
                    ];
                }
            }
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function refuseOrderDelivery(array $data)
    {
        $user = auth()->user();
        $order = Order::where([
            'id' => $data['order_id']
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_refuse_delivery']) && $actions['can_refuse_delivery']) {
                DB::beginTransaction();
                try {
                    $order->update([
                        'status' => OrderStatus::RECEIVE_REFUSED,
                    ]);
                    $reason = RefuseReason::make([
                        'order_id' => $order->id,
                        'reason' => $data['reason']
                    ]);
                    if (isset($data['web']) && isset($data['request']) && $data['request']->hasFile('images')) {
                        $file = $data['request']->file('images');
//                        foreach ($files as $file) {
                        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                        $image_path = 'uploads/generalImages/';
                        $image = UtilsRepository::uploadImage($data['request'], $file, $image_path, $file_id);
                        if ($image) {
                            Image::create([
                                'image' => $image,
                                'refuse_request_id' => $reason->id
                            ]);
                        }
//                        }
                    } else if (isset($data['images']) && !empty($data['images'])) {
                        $images = explode(',', $data['images']);
                        foreach ($images as $image) {
                            Image::where(['id' => $image])
                                ->update([
                                    'refuse_request_id' => $reason->id
                                ]);
                        }
                    }
                    DB::commit();
                    return [
                        'data' => $order->actions(),
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


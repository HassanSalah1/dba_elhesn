<?php

namespace App\Repositories\Api\Order;

use App\Entities\HttpCode;
use App\Entities\NotificationType;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\Status;
use App\Entities\UserRoles;
use App\Http\Resources\OrderDamainDetailsResource;
use App\Jobs\SendNotificationFCMJob;
use App\Models\DamainOrder;
use App\Models\Image;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\User;
use App\Repositories\General\UtilsRepository;
use Illuminate\Support\Facades\DB;

class DamainOrderApiRepository
{

    // add damain order
    public static function addDamainOrder(array $data)
    {

        DB::beginTransaction();
        try {
            $currentUser = auth()->user();
            $user = User::where([
                'phonecode' => $data['phonecode'],
                'phone' => $data['phone']
            ])->first();
            if ($user && $currentUser->id === $user->id) {
                return [
                    'message' => trans('api.You cannot send an embed request to your account'),
                    'code' => HttpCode::ERROR
                ];
            }
            if (!$user) {
                $user = User::create([
                    'phonecode' => $data['phonecode'],
                    'phone' => $data['phone'],
                    'name' => $data['user_name'],
                    'role' => UserRoles::REGISTER,
                    'status' => Status::UNVERIFIED
                ]);
            }
            $orderData = [
                'type' => OrderType::DAMAIN,
                'status' => OrderStatus::WAIT,
                'user_id' => $currentUser->id,
                'user_type' => $data['user_type'],
                'other_user_id' => $user ? $user->id : null,
            ];
            $order = Order::create($orderData);
            $damainOrderData = [
                'order_id' => $order->id,
                'user_name' => $user ? null : $data['user_name'],
                'phonecode' => $user ? null : $data['phonecode'],
                'phone' => $user ? null : $data['phone'],
            ];
            if ($data['user_type'] === OrderUserType::SELLER) {
                $damainOrderData = array_merge($damainOrderData, [
                    'name' => $data['name'],
                    'category_id' => $data['category_id'],
                    'sub_category_id' => isset($data['sub_category_id']) ? $data['sub_category_id'] : null,
                    'sub_sub_category_id' => isset($data['sub_sub_category_id']) ? $data['sub_sub_category_id'] : null,
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'latitude' => isset($data['latitude']) ? $data['latitude'] : null,
                    'longitude' => isset($data['longitude']) ? $data['longitude'] : null,
                    'address' => isset($data['address']) ? $data['address'] : null,
                ]);

                if (isset($data['web']) && isset($data['request']) && $data['request']->hasFile('images')) {
                    $files = $data['request']->file('images');
                    foreach ($files as $file) {
                        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                        $image_path = 'uploads/generalImages/';
                        $image = UtilsRepository::uploadImage($data['request'], $file, $image_path, $file_id);
                        if ($image) {
                            Image::create([
                                'image' => $image,
                                'order_id' => $order->id
                            ]);
                        }
                    }
                } else {
                    $images = explode(',', $data['images']);
                    foreach ($images as $image) {
                        Image::where(['id' => $image])->update(['order_id' => $order->id]);
                    }
                }
            }
            $damainOrder = DamainOrder::create($damainOrderData);
            DB::commit();    // Commiting  ==> There is no problem whatsoever
        } catch (\Exception $e) {
            DB::rollback();   // rollbacking  ==> Something went wrong
            return [
                'message' => trans('api.general_error_message'),
                'code' => HttpCode::ERROR
            ];
        }
        if ($user) {
            if ($user->role === UserRoles::REGISTER) {
                //
                // TODO:: send sms to register
            }
            //
            //  send notification with order

            $notification_obj = [
                'title_key' => 'notification_new_damain_order_title',
                'message_key' => ($data['user_type'] === OrderUserType::SELLER)
                    ? 'notification_new_damain_order_buyer_message' : 'notification_new_damain_order_seller_message',
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => NotificationType::ORDER
            ];
            SendNotificationFCMJob::dispatch($user, $notification_obj, []);
        }
        // return success response
        return [
            'data' => OrderDamainDetailsResource::make($order),
            'message' => trans('api.done_successfully'),
            'code' => HttpCode::SUCCESS
        ];
    }

    // only buyer can do this action
    public static function addProductToDamainOrder(array $data)
    {
        $user = auth()->user();
        $order = Order::where([
            'type' => OrderType::DAMAIN,
            'status' => OrderStatus::WAIT,
            'id' => $data['order_id'],
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['add_product']) && $actions['add_product']) {
                DB::beginTransaction();
                try {
                    $damainOrderData = [
                        'name' => $data['name'],
                        'category_id' => $data['category_id'],
                        'sub_category_id' => isset($data['sub_category_id']) ? $data['sub_category_id'] : null,
                        'sub_sub_category_id' => isset($data['sub_sub_category_id']) ? $data['sub_sub_category_id'] : null,
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'latitude' => isset($data['latitude']) ? $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? $data['longitude'] : null,
                        'address' => isset($data['address']) ? $data['address'] : null,
                    ];

                    if (isset($data['web']) && isset($data['request']) && $data['request']->hasFile('images')) {
                        $files = $data['request']->file('images');
                        foreach ($files as $file) {
                            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                            $image_path = 'uploads/generalImages/';
                            $image = UtilsRepository::uploadImage($data['request'], $file, $image_path, $file_id);
                            if ($image) {
                                Image::create([
                                    'image' => $image,
                                    'order_id' => $order->id
                                ]);
                            }
                        }
                    } else if (isset($data['images']) && !empty($data['images'])) {
                        $images = explode(',', $data['images']);
                        foreach ($images as $image) {
                            Image::where(['id' => $image])->update(['order_id' => $order->id]);
                        }
                    }
                    $order->damainOrder->update($damainOrderData);
                    DB::commit();    // Commiting  ==> There is no problem whatsoever
                } catch (\Exception $e) {
                    DB::rollback();   // rollbacking  ==> Something went wrong
                    return [
                        'message' => trans('api.general_error_message'),
                        'code' => HttpCode::ERROR
                    ];
                }

                //
                $notification_obj = [
                    'title_key' => 'notification_add_product_damain_order_title',
                    'message_key' => 'notification_add_product_damain_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];
                SendNotificationFCMJob::dispatch($user, $notification_obj);

                return [
                    'data' => $order->damainActions(),
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

    // only seller can do this action
    public static function editDamainOrderProduct(array $data)
    {
        $user = auth()->user();
        $order = Order::where([
            'type' => OrderType::DAMAIN,
            'status' => OrderStatus::REFUSED,
            'id' => $data['order_id'],
        ])->first();
        if ($order) {
            $actions = $order->actions();
            if (isset($actions['can_edit']) && $actions['can_edit']) {
                DB::beginTransaction();
                try {
                    $damainOrderData = [
                        'name' => $data['name'],
                        'category_id' => $data['category_id'],
                        'sub_category_id' => isset($data['sub_category_id']) ? $data['sub_category_id'] : null,
                        'sub_sub_category_id' => isset($data['sub_sub_category_id']) ? $data['sub_sub_category_id'] : null,
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'latitude' => isset($data['latitude']) ? $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? $data['longitude'] : null,
                        'address' => isset($data['address']) ? $data['address'] : null,
                    ];
                    if (isset($data['web']) && isset($data['request']) && $data['request']->hasFile('images')) {
                        $files = $data['request']->file('images');
                        foreach ($files as $file) {
                            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                            $image_path = 'uploads/generalImages/';
                            $image = UtilsRepository::uploadImage($data['request'], $file, $image_path, $file_id);
                            if ($image) {
                                Image::create([
                                    'image' => $image,
                                    'order_id' => $order->id
                                ]);
                            }
                        }
                    } else if (isset($data['images']) && !empty($data['images'])) {
                        $images = explode(',', $data['images']);
                        foreach ($images as $image) {
                            Image::where(['id' => $image])->update(['order_id' => $order->id]);
                        }
                    }
                    $order->update(['status' => OrderStatus::EDITED]);
                    $order->damainOrder->update($damainOrderData);
                    DB::commit();    // Commiting  ==> There is no problem whatsoever
                } catch (\Exception $e) {
                    DB::rollback();   // rollbacking  ==> Something went wrong
                    return [
                        'message' => trans('api.general_error_message'),
                        'code' => HttpCode::ERROR
                    ];
                }
                //  send notification
                $notification_obj = [
                    'title_key' => 'notification_add_product_damain_order_title',
                    'message_key' => 'notification_add_product_damain_order_message',
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => NotificationType::ORDER
                ];
                SendNotificationFCMJob::dispatch($user, $notification_obj);

                return [
                    'data' => $order->damainActions(),
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

    public static function getDamainOrderDetails(array $data)
    {
        $order = Order::where([
            'id' => $data['id'],
            'type' => OrderType::DAMAIN
        ])->where(function ($query) {
            $user = auth()->user();
            $query->where(['user_id' => $user->id]);
            $query->orWhere(['other_user_id' => $user->id]);
        })->first();
        if ($order) {
            return [
                'data' => OrderDamainDetailsResource::make($order),
                'message' => 'success',
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

}

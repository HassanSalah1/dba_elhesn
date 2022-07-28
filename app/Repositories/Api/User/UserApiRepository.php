<?php

namespace App\Repositories\Api\User;


use App\Entities\HttpCode;
use App\Entities\OrderStatus;
use App\Entities\ProductType;
use App\Http\Resources\ChatMessagesResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\BankAccount;
use App\Models\Chat;
use App\Models\Favourite;
use App\Models\Notification;
use App\Models\Product;
use App\Repositories\Api\Auth\AuthApiRepository;
use App\Repositories\General\UtilsRepository;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class UserApiRepository
{


    public static function getProfile(array $data)
    {
        $user = auth()->user();
        return [
            'data' => UserResource::make($user),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function editProfile(array $data)
    {
        $userData = [
            'name' => (isset($data['name'])) ? $data['name'] : $data['user']->name,
            'email' => (isset($data['email'])) ? $data['email'] : $data['user']->email,
            'edit_phone' => (isset($data['phone']) && $data['phone'] !== $data['user']->phone) ?
                $data['phone'] : null,
            'edit_phonecode' => (isset($data['phone']) && $data['phone'] !== $data['user']->phone) ?
                $data['phonecode'] : null,
            'city_id' => (isset($data['city_id'])) ? $data['city_id'] : $data['user']->city_id,
            'address' => (isset($data['address'])) ? $data['address'] : $data['user']->address,
            'latitude' => (isset($data['latitude'])) ? $data['latitude'] : $data['user']->latitude,
            'longitude' => (isset($data['longitude'])) ? $data['longitude'] : $data['user']->longitude,
        ];

        if (isset($data['password']) && !empty($data['password'])) {
            $userData['password'] = $data['password'];
        }
        if ($data['request']->hasFile('image')) {
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/users/';
            $data['image'] = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($data['image'] !== false) {
                if ($data['user']->image !== null && file_exists($data['user']->image)) {
                    unlink($data['user']->image);
                }
                $userData['image'] = $data['image'];
            }
        }

        if ($userData['edit_phone'] !== null) {
            $is_sent = AuthApiRepository::sendVerificationCode($data['user']);
        }

        if (isset($data['banks'])) {
            $data['banks'] = json_decode($data['banks'], true);
            BankAccount::where(['user_id' => $data['user']->id])->delete();
            foreach ($data['banks'] as $bank) {
                $bankAccount = new BankAccount();
                if (isset($bank['id'])) {
                    $obj = BankAccount::withTrashed()->where([
                        'id' => $bank['id'],
                        'user_id' => $data['user']->id
                    ])->first();
                    if ($obj) {
                        $bankAccount = $obj;
                        $bankAccount->restore();
                    }
                }

                $bankAccount->user_id = $data['user']->id;
                $bankAccount->ipan = $bank['ipan'];
                $bankAccount->bank_name = $bank['bank_name'];
                $bankAccount->save();
            }
        } else if (isset($data['bank_name']) && isset($data['ipan'])) {
            $bankAccount = null;
            if (isset($data['bank_id'])) {
                $bankAccount = BankAccount::withTrashed()
                    ->where([
                        'id' => $data['bank_id'],
                        'user_id' => $data['user']->id
                    ])->first();
            }
            if (!$bankAccount) {
                $bankAccount = new BankAccount();
            }
            $bankAccount->user_id = $data['user']->id;
            $bankAccount->ipan = $data['ipan'];
            $bankAccount->bank_name = $data['bank_name'];
            $bankAccount->save();
        }

        $data['user']->update($userData);
        return [
            'data' => UserResource::make($data['user']),
            'message' => trans('api.done_successfully'),
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getMyProducts(array $data)
    {
        $web = isset($data['web']) ? 1 : 0;
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $products = Product::withoutTrashed()
            ->where(function ($query) {
                $query->doesntHave('normalOrder')
                    ->orWhereHas('normalOrder.order', function ($query) {
                        $query->whereNotIn('status', [
                            OrderStatus::WAIT,
                            OrderStatus::RECEIVE_REFUSED,
                            OrderStatus::COMPLETED,
                            OrderStatus::SHIPPED,
                            OrderStatus::PROGRESS,
                            OrderStatus::PAYMENT_APPROVED,
                            OrderStatus::ACCEPTED,
                        ]);
                    });
            })
            ->where(['user_id' => auth()->id()])
            ->where(function ($query) use ($data) {
                if(isset($data['type'])){
                    if($data['type'] === ProductType::BID){
                        $query->where(['type' => ProductType::BID]);
                    }else if($data['type'] === ProductType::DIRECT){
                        $query->where(['type' => ProductType::DIRECT]);
                    }
                }
            })
            ->orderBy('id', 'desc');

        if($web){
            $products = ProductResource::collection($products->get())->toArray($data['request']);
        }else {
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

    public static function toggleFavouriteProduct(array $data)
    {
        $userId = auth()->user()->id;
        $product = Product::withoutTrashed()
            ->where([
                'id' => $data['id'],
                ['user_id', '!=', $userId]
            ])->first();
        if ($product) {
            $favouriteData = [
                'product_id' => $product->id,
                'user_id' => $userId
            ];
            $is_favourite = 0;
            $favourite = Favourite::where($favouriteData)->first();
            if ($favourite) {
                $favourite->forceDelete();
            } else {
                Favourite::create($favouriteData);
                $is_favourite = 1;
            }
            return [
                'data' => [
                    'is_favourite' => $is_favourite
                ],
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function getMyFavouriteProducts(array $data)
    {
        $web = isset($data['web']) ? 1 : 0;
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $products = Product::withoutTrashed()
            ->join('favourites', 'favourites.product_id', '=', 'products.id')
            ->select(['products.id', 'name', 'description', 'category_id', 'sub_category_id'
                , 'sub_sub_category_id', 'price', 'max_price', 'show_user', 'negotiation',
                'percent', 'period', 'type'])
            ->where([
                'favourites.user_id' => auth()->id()
            ])
            ->orderBy('id', 'desc');

        if($web){
            $products = ProductResource::collection($products->get())->toArray($data['request']);
        }else {
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

    public static function getMyNotifications(array $data)
    {
        $web = isset($data['web']) ? 1 : 0;
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $notifications = Notification::where(['user_id' => auth()->user()->id])
            ->orderBy('id', 'desc');

        if ($web){
            $notifications = NotificationResource::collection($notifications->get());
        }else {
            $count = $notifications->count();
            $notifications = $notifications->offset($offset)
                ->skip($offset)
                ->take($per_page)
                ->get();

            $notifications = new Paginator(NotificationResource::collection($notifications), $count, $per_page);
        }
        return [
            'data' => $notifications,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getMyChats(array $data)
    {
        $page = (isset($data['page'])) ? $data['page'] : 1;
        $per_page = 20;
        $offset = $per_page * ($page - 1);

        $chats = Chat::where(['user_id' => auth()->user()->id])
            ->orWhere(['owner_id' => auth()->user()->id])
            ->orderBy('id', 'desc');
        $count = $chats->count();
        $chats = $chats->offset($offset)
            ->skip($offset)
            ->take($per_page)
            ->get();
        $chats = new Paginator(ChatResource::collection($chats), $count, $per_page);
        return [
            'data' => $chats,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getChatDetails(array $data)
    {
        $chat = Chat::where(function ($query) {
            $query->where(['user_id' => auth()->user()->id])
                ->orWhere(['owner_id' => auth()->user()->id]);
        })
            ->where(['id' => $data['id']])
            ->orderBy('id', 'desc')
            ->first();
        if ($chat) {
            $page = (isset($data['page'])) ? $data['page'] : 1;
            $per_page = 20;
            $offset = $per_page * ($page - 1);

            $messages = $chat->message()->orderBy('id', 'DESC');

            $messages->update([
                'seen' => 1
            ]);

            $count = $messages->count();
            $messages = $messages->offset($offset)
                ->skip($offset)
                ->take($per_page)
                ->get();

            $messages = new Paginator(ChatMessagesResource::collection($messages), $count, $per_page);

            return [
                'data' => $messages,
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

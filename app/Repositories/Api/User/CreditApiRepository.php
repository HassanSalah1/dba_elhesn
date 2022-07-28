<?php

namespace App\Repositories\Api\User;


use Alhoqbani\SmsaWebService\Models\Customer;
use Alhoqbani\SmsaWebService\Models\Shipment;
use Alhoqbani\SmsaWebService\Models\Shipper;
use Alhoqbani\SmsaWebService\Smsa;
use App\Entities\BankTransferStatus;
use App\Entities\CreditType;
use App\Entities\HttpCode;
use App\Entities\NotificationType;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\PaymentMethod;
use App\Entities\ShipmentType;
use App\Entities\WithdrawRequestStatus;
use App\Http\Resources\CreditOrderHistoryResource;
use App\Jobs\SendNotificationFCMJob;
use App\Models\BankTransfer;
use App\Models\Credit;
use App\Models\Order;
use App\Models\User;
use App\Models\WithdrawRequest;
use App\Repositories\General\Arbpg;
use App\Repositories\General\UtilsRepository;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;

class CreditApiRepository
{

    public static function chargeMyWallet(array $data)
    {
        $user = auth()->user();
        $credit = null;
        if ($data['payment_method'] == PaymentMethod::ONLINE_PAYMENT) {
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
                $user->id, $data['amount'], $data['payment_method'], $apiUrl, $webUrl, $data['platform'], 'charge');
            if ($response['status'] == 200) {
                return [
                    'message' => trans('api.done_successfully'),
                    'data' => ['url' => $response['url'],],
                    'code' => HttpCode::SUCCESS
                ];
            }
        } else if ($data['payment_method'] == PaymentMethod::BANK_TRANSFER) {
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/bankTransfers/';
            $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($image !== false) {
                $credit = BankTransfer::create([
                    'amount' => $data['amount'],
                    'user_id' => $user->id,
                    'bank_id' => $data['bank_id'],
                    'image' => $image,
                    'status' => BankTransferStatus::WAIT
                ]);
            }
        }

        if ($credit) {
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        } else {
            return [
                'message' => trans('api.general_error_message'),
                'code' => HttpCode::ERROR
            ];
        }
    }

    public static function getMyWallet(array $data)
    {
        $web = (isset($data['web'])) ? 1 : 0;
        $user = auth()->user();
        $orders = Order::where(['status' => OrderStatus::COMPLETED])
            ->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    $query->orWhere(function ($query) use ($user) {
                        $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    });
                })->orWhere(function ($query) use ($user) {
                    $query->where(['other_user_id' => $user->id, 'user_type' => OrderUserType::SELLER]);
                    $query->orWhere(function ($query) use ($user) {
                        $query->where(['user_id' => $user->id, 'user_type' => OrderUserType::BUYER]);
                    });
                });
            })
            ->orderBy('id', 'DESC');

        if ($web) {
            $paginated_orders = CreditOrderHistoryResource::collection($orders->get())->toArray($data['request']);
        } else {
            $page = (isset($data['page'])) ? $data['page'] : 1;
            $per_page = 20;
            $offset = $per_page * ($page - 1);
            $count = $orders->count();
            $paginated_orders = $orders->offset($offset)
                ->skip($offset)
                ->take($per_page)
                ->get();
            $paginated_orders = new Paginator(CreditOrderHistoryResource::collection($paginated_orders), $count, $per_page);
        }
        return [
            'data' => [
                'balance' => $user->balance,
                'total_orders' => $user->total_completed_orders,
                'history_orders' => $paginated_orders
            ],
            'message' => trans('api.done_successfully'),
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function requestWithdraw(array $data)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            if (($data['type'] === 'part' && $data['amount'] > $user->real_balance)
                || ($data['type'] === 'all' && $user->real_balance <= 0)) {
                return [
                    'message' => trans('api.no_enough_real_balance'),
                    'code' => HttpCode::ERROR
                ];
            }

            $amount = $data['type'] === 'part' ? $data['amount'] : $user->real_balance;
            WithdrawRequest::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => WithdrawRequestStatus::WAIT
            ]);
            DB::commit();
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        } catch (\Exception $ex) {
            DB::rollBack();
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function payDone(array $data)
    {
        $trandata = $data['trandata'];
        $arbPg = new Arbpg();

        $result = $arbPg->getresult($trandata);
        if ($result['status'] == 200) {
            $object_id = intval($result['data']['udf1']);
            $status = $result['data']['udf3'];
            $user_id = intval($result['data']['udf2']);
            $actionType = $result['data']['udf4'];
            $done = false;
            if ($actionType === 'pay') {
                $order = Order::find($object_id);
                if ($status == OrderStatus::PROGRESS) {
                    $order->update([
                        'status' => OrderStatus::PROGRESS
                    ]);
                    $done = true;
                }
            } else if ($actionType === 'shipment') {
                $order = Order::find($object_id);
                $user = User::find($user_id);
                $user = ($user->id === $order->user_id && $order->user_type === OrderUserType::BUYER)
                    ? $order->other_user : $order->user;
                ////////

                // Create a customer
                $customerObj = $order->user_type === OrderUserType::BUYER ? $order->user : $order->other_user;
                $shipperObj = $order->user_type === OrderUserType::BUYER ? $order->other_user : $order->user;

                try {
                    $passKey = config('smsa.passkey');
                    $smsa = new Smsa($passKey);
                    $customer = new Customer(
                        $customerObj->name, //customer name
                        $user->full_phone, // mobile number. must be > 9 digits
                        $user->address, // street address
                        $user->city->name // city
                    );

                    $shipment = new Shipment(
                        $order->id . time(), // Refrence number
                        $customer, // Customer object
                        Shipment::TYPE_DLV // Shipment type.
                    );
                    // To add shipper details to the shipment
                    $shipper = new Shipper(
                        $shipperObj->name, // shipper name
                        '', // contact name
                        $shipperObj->address, // address line 1
                        $shipperObj->city->name, // city
                        $shipperObj->city->country->name, // country
                        $shipperObj->full_phone // phone
                    );

                    $shipment->setShipper($shipper);
                    $shipment->setWeight(1);
                    $shipment->setItemsCount(1)
                        ->setValue($order->total_price)
                        ->setCashOnDelivery(0);
                    $awb = $smsa->createShipment($shipment);
                } catch (\Exception $exception) {
                }
                $order->update([
                    'shipment_id' => $status,
                    'shipment_type' => ShipmentType::APP_SHIP,
                    'status' => OrderStatus::SHIPPED,
                    'shipment_number' => isset($awb) && $awb ? $awb->data : null
                ]);
                DB::commit();


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

                /////
                $done = true;

            } else if ($actionType === 'charge') {
                $creditData = [
                    'user_id' => $object_id,
                    'amount' => $result['data']['amt'],
                    'type' => CreditType::CHARGE,
                    'payment_method_id' => $status
                ];
                $credit = Credit::create($creditData);
                $done = true;
            }
            if ($done) {
                return response()->json([], 200);
            }
        }
        return redirect()->to(url('/api/v1/payment-error'));
    }

}

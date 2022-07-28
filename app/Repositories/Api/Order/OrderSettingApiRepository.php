<?php

namespace App\Repositories\Api\Order;

use App\Entities\HttpCode;
use App\Http\Resources\BankAccountResource;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Resources\ShipmentResource;
use App\Models\BankAccount;
use App\Models\PaymentMethod;
use App\Models\Shipment;

class OrderSettingApiRepository
{

    // get shipments
    public static function getShipments(array $data)
    {
        $shipments = Shipment::withoutTrashed()->orderBy('id', 'DESC')->get();
        // return success response
        return [
            'data' => ShipmentResource::collection($shipments),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getPaymentMethods(array $data)
    {
        $paymentMethods = PaymentMethod::withoutTrashed()->get();
        // return success response
        return [
            'data' => PaymentMethodResource::collection($paymentMethods),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getBankAccounts(array $data)
    {
        $web = (isset($data['web'])) ? 1 : 0;
        $bankAccounts = BankAccount::withoutTrashed()->where([
            'user_id' => null
        ])->get();
        // return success response
        if ($web) {
            $bankAccounts = BankAccountResource::collection($bankAccounts)->toArray($data['request']);
        } else {
            $bankAccounts = BankAccountResource::collection($bankAccounts);
        }
        return [
            'data' => $bankAccounts,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

}

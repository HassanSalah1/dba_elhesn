<?php

namespace App\Services\Api\User;


use App\Entities\PaymentMethod;
use App\Repositories\Api\User\CreditApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class CreditApiService
{


    public static function chargeMyWallet(array $data)
    {
        $keys = [
            'payment_method' => 'required|in:' . implode(',', PaymentMethod::chargeWallet()),
            'amount' => 'required|min:1',
            'bank_id' => 'required_if:payment_method,==,' . PaymentMethod::BANK_TRANSFER,
            'image' => 'required_if:payment_method,==,' . PaymentMethod::BANK_TRANSFER
        ];

        if (isset($data['payment_method']) && $data['payment_method'] == PaymentMethod::BANK_TRANSFER) {
            $keys['image'] .= '|mimes:png,jpg,jpeg';
        }else if (isset($data['payment_method']) && $data['payment_method'] == PaymentMethod::ONLINE_PAYMENT) {
            $keys = array_merge($keys, [
                'card_number' => 'required',
                'expiry_month' => 'required',
                'expiry_year' => 'required',
                'cvv' => 'required',
                'holder_name' => 'required',
            ]);
        }

        $messages = [
            'required' => trans('api.required_error_message'),
            'required_if' => trans('api.required_error_message'),
            'payment_method.in' => trans('api.payment_method_in_error_message'),
            'mimes' => trans('api.mimes_error_message'),
            'min' => trans('api.min_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = CreditApiRepository::chargeMyWallet($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getMyWallet(array $data)
    {
        $response = CreditApiRepository::getMyWallet($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function requestWithdraw(array $data)
    {
        $keys = [
            'type' => 'required|in:all,part',
            'amount' => 'required_if:type,==,part',
        ];

        if (isset($data['type']) && $data['type'] === 'part') {
            $keys['amount'] .= '|min:1';
        }
        $messages = [
            'required' => trans('api.required_error_message'),
            'required_if' => trans('api.required_error_message'),
            'min' => trans('api.min_error_message')
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = CreditApiRepository::requestWithdraw($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function payDone(array $data)
    {
        return CreditApiRepository::payDone($data);
    }


}

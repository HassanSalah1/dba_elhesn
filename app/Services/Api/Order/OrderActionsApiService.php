<?php

namespace App\Services\Api\Order;

use App\Entities\ShipmentType;
use App\Models\PaymentMethod;
use App\Repositories\Api\Order\OrderActionsApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class OrderActionsApiService
{


    public static function acceptOrder(array $data)
    {
        $keys = [
            'order_id' => 'required'
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::acceptOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function refuseOrder(array $data)
    {
        $keys = [
            'order_id' => 'required',
            'reason' => 'required'
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::refuseOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function cancelOrder(array $data)
    {
        $keys = [
            'order_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::cancelOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function payOrder(array $data)
    {
        $keys = [
            'order_id' => 'required',
            'payment_method_id' => 'required'
        ];
        if (isset($data['payment_method_id']) && $data['payment_method_id'] == \App\Entities\PaymentMethod::BANK_TRANSFER) {
            $keys = array_merge($keys, [
                'bank_id' => 'required',
                'image' => 'required|image|max:3072'
            ]);
        } else if (isset($data['payment_method_id']) && $data['payment_method_id'] == \App\Entities\PaymentMethod::ONLINE_PAYMENT) {
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
            'image' => trans('api.image_error_message'),
            'max' => trans('api.file_max_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::payOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function makeOrderShipped(array $data)
    {
        $keys = [
            'order_id' => 'required',
            'shipment_id' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
            'shipment_type' => 'required',
            'card_number' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
            'expiry_month' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
            'expiry_year' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
            'holder_name' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
            'cvv' => 'required_if:shipment_type,==,' . ShipmentType::APP_SHIP,
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::makeOrderShipped($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function acceptOrderDelivery(array $data)
    {
        $keys = [
            'order_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::acceptOrderDelivery($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function refuseOrderDelivery(array $data)
    {
        $keys = [
            'order_id' => 'required',
            'reason' => 'required',
            'images' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderActionsApiRepository::refuseOrderDelivery($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

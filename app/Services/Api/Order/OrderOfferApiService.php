<?php

namespace App\Services\Api\Order;

use App\Repositories\Api\Order\OrderApiRepository;
use App\Repositories\Api\Order\OrderOfferApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class OrderOfferApiService
{

    public static function addProductNegotiationOffer(array $data)
    {
        $keys = [
            'product_id' => 'required',
            'price' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::addProductNegotiationOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function acceptNegotiationOffer(array $data)
    {
        $keys = [
            'offer_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::acceptNegotiationOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function refuseNegotiationOffer(array $data)
    {
        $keys = [
            'offer_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::refuseNegotiationOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addProductBidOffer(array $data)
    {
        $keys = [
            'product_id' => 'required',
            'price' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::addProductBidOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function acceptBidOffer(array $data)
    {
        $keys = [
            'offer_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::acceptBidOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function refuseBidOffer(array $data)
    {
        $keys = [
            'offer_id' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = OrderOfferApiRepository::refuseBidOffer($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

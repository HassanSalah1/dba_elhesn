<?php

namespace App\Services\Api\Order;

use App\Entities\HttpCode;
use App\Entities\OrderUserType;
use App\Entities\ShipmentType;
use App\Entities\Status;
use App\Models\Country;
use App\Repositories\Api\Order\DamainOrderApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class DamainOrderApiService
{

    public static function addDamainOrder(array $data)
    {
        $country = null;
        if (isset($data['phonecode'])) {
            $country = Country::where([
                'country_code' => $data['phonecode'],
                'status' => Status::ACTIVE,
            ])->first();
            if (!$country) {
                return UtilsRepository::handleResponseApi([
                    'message' => trans('api.general_error_message'),
                    'code' => HttpCode::ERROR
                ]);
            }
        }
        $keys = [
            'user_name' => 'required',
            'phonecode' => 'required',
            'phone' => 'required' . (($country) ? '|phone:' . strtoupper($country->code) . ',mobile' : ''),
            'user_type' => 'required|in:' . implode(',', OrderUserType::getKeys()),
        ];
        if (isset($data['user_type']) && $data['user_type'] === OrderUserType::SELLER) {
            $keys = array_merge($keys, [
                'name' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'price' => 'required',
//                'latitude' => 'required',
//                'longitude' => 'required',
                'address' => 'required',
                'images' => 'required'
            ]);
        }
        $messages = [
            'required' => trans('api.required_error_message'),
            'user_type.in' => trans('api.user_type_error_message'),
            'phone' => trans('api.valid_phone_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = DamainOrderApiRepository::addDamainOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addProductToDamainOrder(array $data)
    {

        $keys = [
            'order_id' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'address' => 'required',
            'images' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = DamainOrderApiRepository::addProductToDamainOrder($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function editDamainOrderProduct(array $data)
    {
        $keys = [
            'order_id' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'address' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = DamainOrderApiRepository::editDamainOrderProduct($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function getDamainOrderDetails(array $data)
    {
        $response = DamainOrderApiRepository::getDamainOrderDetails($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

<?php

namespace App\Services\Api\Product;


use App\Entities\ProductType;
use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class ProductApiService
{

    public static function getNegotiationPercent(array $data)
    {
        $response = ProductApiRepository::getNegotiationPercent($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getNegotiationPeriod(array $data)
    {
        $response = ProductApiRepository::getNegotiationPeriod($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function uploadProductImage(array $data)
    {
        $keys = [
            'image' => 'required|image|max:3072'
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
            'image' => trans('api.image_error_message'),
            'max' => trans('api.file_max_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = ProductApiRepository::uploadProductImage($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function removeProductImage(array $data)
    {
        $response = ProductApiRepository::removeProductImage($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addProduct(array $data)
    {
        $keys = [
            'name' => 'required',
            'description' => 'required|string|max:1000',
            'category_id' => 'required',
//            'sub_category_id' => 'required',
//            'sub_sub_category_id' => 'required',
            'price' => 'required',
            'type' => 'required|in:' . implode(',', ProductType::getKeys()),
            'images' => 'required'
        ];
        if (isset($data['type']) && $data['type'] === ProductType::DIRECT) {
            $keys = array_merge($keys, [
                'show_user' => 'required',
                'negotiation' => 'required',
            ]);
            if (isset($data['negotiation']) && intval($data['negotiation']) === 1) {
                $keys = array_merge($keys, [
                    'percent' => 'required',
                ]);
            }
        } else if (isset($data['type']) && $data['type'] === ProductType::BID) {
            $keys = array_merge($keys, [
//                'max_price' => 'required',
                'period' => 'required',
            ]);
        }
        $messages = [
            'required' => trans('api.required_error_message'),
            'description.max' => trans('api.description_maxlength_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = ProductApiRepository::addProduct($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function editProduct(array $data)
    {
        $response = ProductApiRepository::editProduct($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function deleteProduct(array $data)
    {
        $response = ProductApiRepository::deleteProduct($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addProductComment(array $data)
    {
        $keys = [
            'comment' => 'required|string|max:1000',
            'product_id' => 'required'
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
            'description.max' => trans('api.comment_maxlength_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = ProductApiRepository::addProductComment($data);
        return UtilsRepository::handleResponseApi($response);

    }

    public static function getProductDetails(array $data)
    {
        $response = ProductApiRepository::getProductDetails($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

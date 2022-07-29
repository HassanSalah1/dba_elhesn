<?php

namespace App\Services\Api\Setting;

use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class SettingApiService
{


    public static function getTerms(array $data)
    {
        $response = SettingApiRepository::getTerms($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getContactDetails(array $data)
    {
        $response = SettingApiRepository::getContactDetails($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function addContact(array $data)
    {
        $data['user_id'] = auth('api')->id();
        $keys = [
            'contact_type' => 'required',
            'message' => 'required',
            'name' => 'required_without:user_id',
            'email' => 'required_without:user_id',
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys);
        if ($validated !== true) {
            return $validated;
        }
        $response = SettingApiRepository::addContact($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getTeams(array $data)
    {
        $response = SettingApiRepository::getTeams($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function getGallery(array $data)
    {
        $response = SettingApiRepository::getGallery($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getNews(array $data)
    {
        $response = SettingApiRepository::getNews($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getActions(array $data)
    {
        $response = SettingApiRepository::getActions($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

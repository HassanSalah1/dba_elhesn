<?php

namespace App\Services\Api\Setting;

use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class SettingApiService
{

    public static function getAbout(array $data)
    {
        $response = SettingApiRepository::getAbout($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getTerms(array $data)
    {
        $response = SettingApiRepository::getTerms($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getPrivacy(array $data)
    {
        $response = SettingApiRepository::getPrivacy($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getFaqs(array $data)
    {
        $response = SettingApiRepository::getFaqs($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getUserGuides(array $data)
    {
        $response = SettingApiRepository::getUserGuides($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getContactTypes(array $data)
    {
        $response = SettingApiRepository::getContactTypes($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getIntros(array $data)
    {
        $response = SettingApiRepository::getIntros($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getCountries(array $data)
    {
        $response = SettingApiRepository::getCountries($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function getCities(array $data)
    {
        $response = SettingApiRepository::getCities($data);
        return UtilsRepository::handleResponseApi($response);
    }

    ////////////////////////
    public static function addContact(array $data)
    {
        $keys = [
            'contact_type_id' => 'required',
            'message' => 'required',
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys);
        if ($validated !== true) {
            return $validated;
        }
        $response = SettingApiRepository::addContact($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function uploadGeneralImage(array $data)
    {
        $keys = [
            'image' => 'required|image|max:3072'
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
            'image'=> trans('api.image_error_message'),
            'max' => trans('api.file_max_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = SettingApiRepository::uploadGeneralImage($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function removeGeneralImage(array $data)
    {
        $response = SettingApiRepository::removeGeneralImage($data);
        return UtilsRepository::handleResponseApi($response);
    }

}

?>

<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\UserGuideRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class UserGuideService
{


    public static function getUserGuidesData(array $data)
    {
        return UserGuideRepository::getUserGuidesData($data);
    }

    public static function addUserGuide(array $data)
    {
        $rules = [
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = UserGuideRepository::addUserGuide($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteUserGuide(array $data)
    {
        $response = UserGuideRepository::deleteUserGuide($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getUserGuideData(array $data)
    {
        $response = UserGuideRepository::getUserGuideData($data);
        return UtilsRepository::response($response);
    }

    public static function editUserGuide(array $data)
    {
        $rules = [
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = UserGuideRepository::editUserGuide($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}

?>

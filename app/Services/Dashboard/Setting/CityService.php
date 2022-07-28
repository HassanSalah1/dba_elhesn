<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\CityRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class CityService
{


    public static function getCitiesData(array $data)
    {
        return CityRepository::getCitiesData($data);
    }

    public static function deleteCity(array $data)
    {
        $response = CityRepository::deleteCity($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function restoreCity(array $data)
    {
        $response = CityRepository::restoreCity($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}
?>

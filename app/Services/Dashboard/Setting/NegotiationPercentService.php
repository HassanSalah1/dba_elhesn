<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\NegotiationPercentRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class NegotiationPercentService
{


    public static function getNegotiationPercentsData(array $data)
    {
        return NegotiationPercentRepository::getNegotiationPercentsData($data);
    }

    public static function addNegotiationPercent(array $data)
    {
        $rules = [
            'percent' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NegotiationPercentRepository::addNegotiationPercent($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteNegotiationPercent(array $data)
    {
        $response = NegotiationPercentRepository::deleteNegotiationPercent($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getNegotiationPercentData(array $data)
    {
        $response = NegotiationPercentRepository::getNegotiationPercentData($data);
        return UtilsRepository::response($response);
    }

    public static function editNegotiationPercent(array $data)
    {
        $rules = [
            'percent' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NegotiationPercentRepository::editNegotiationPercent($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}
?>

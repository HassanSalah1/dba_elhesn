<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\NegotiationPeriodRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class NegotiationPeriodService
{


    public static function getNegotiationPeriodsData(array $data)
    {
        return NegotiationPeriodRepository::getNegotiationPeriodsData($data);
    }

    public static function addNegotiationPeriod(array $data)
    {
        $rules = [
            'period' => 'required',
            'type' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NegotiationPeriodRepository::addNegotiationPeriod($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteNegotiationPeriod(array $data)
    {
        $response = NegotiationPeriodRepository::deleteNegotiationPeriod($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getNegotiationPeriodData(array $data)
    {
        $response = NegotiationPeriodRepository::getNegotiationPeriodData($data);
        return UtilsRepository::response($response);
    }

    public static function editNegotiationPeriod(array $data)
    {
        $rules = [
            'period' => 'required',
            'type' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NegotiationPeriodRepository::editNegotiationPeriod($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}
?>

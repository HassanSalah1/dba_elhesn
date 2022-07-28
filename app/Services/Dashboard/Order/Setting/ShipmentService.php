<?php
namespace App\Services\Dashboard\Order\Setting;

use App\Repositories\Dashboard\Order\Setting\ShipmentRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class ShipmentService
{

    public static function getShipmentsData(array $data)
    {
        return ShipmentRepository::getShipmentsData($data);
    }

    public static function addShipment(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',
            'image' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = ShipmentRepository::addShipment($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteShipment(array $data)
    {
        $response = ShipmentRepository::deleteShipment($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getShipmentData(array $data)
    {
        $response = ShipmentRepository::getShipmentData($data);
        return UtilsRepository::response($response);
    }

    public static function restoreShipment(array $data)
    {
        $response = ShipmentRepository::restoreShipment($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function editShipment(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'price' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = ShipmentRepository::editShipment($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

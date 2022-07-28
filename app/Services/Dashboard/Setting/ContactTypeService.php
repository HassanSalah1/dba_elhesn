<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\ContactTypeRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class ContactTypeService
{

    public static function getContactTypesData(array $data)
    {
        return ContactTypeRepository::getContactTypesData($data);
    }

    public static function addContactType(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = ContactTypeRepository::addContactType($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteContactType(array $data)
    {
        $response = ContactTypeRepository::deleteContactType($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getContactTypeData(array $data)
    {
        $response = ContactTypeRepository::getContactTypeData($data);
        return UtilsRepository::response($response);
    }

    public static function restoreContactType(array $data)
    {
        $response = ContactTypeRepository::restoreContactType($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function editContactType(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = ContactTypeRepository::editContactType($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

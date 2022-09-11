<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\CommitteeRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class CommitteeService
{


    public static function getCommitteesData(array $data)
    {
        return CommitteeRepository::getCommitteesData($data);
    }

    public static function addCommittee(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = CommitteeRepository::addCommittee($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteCommittee(array $data)
    {
        $response = CommitteeRepository::deleteCommittee($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function restoreCommittee(array $data)
    {
        $response = CommitteeRepository::restoreCommittee($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getCommitteeData(array $data)
    {
        $response = CommitteeRepository::getCommitteeData($data);
        return UtilsRepository::response($response);
    }

    public static function editCommittee(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = CommitteeRepository::editCommittee($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}

?>

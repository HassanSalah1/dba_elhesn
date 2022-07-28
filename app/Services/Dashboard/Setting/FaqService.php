<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\FaqRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class FaqService
{


    public static function getFaqsData(array $data)
    {
        return FaqRepository::getFaqsData($data);
    }

    public static function addFaq(array $data)
    {
        $rules = [
            'question_ar' => 'required',
            'question_en' => 'required',
            'answer_ar' => 'required',
            'answer_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = FaqRepository::addFaq($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteFaq(array $data)
    {
        $response = FaqRepository::deleteFaq($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getFaqData(array $data)
    {
        $response = FaqRepository::getFaqData($data);
        return UtilsRepository::response($response);
    }

    public static function editFaq(array $data)
    {
        $rules = [
            'question_ar' => 'required',
            'question_en' => 'required',
            'answer_ar' => 'required',
            'answer_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = FaqRepository::editFaq($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}
?>

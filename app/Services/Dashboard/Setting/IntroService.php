<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\IntroRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class IntroService
{


    public static function getIntrosData(array $data)
    {
        return IntroRepository::getIntrosData($data);
    }

    public static function addIntro(array $data)
    {
        $rules = [
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required'
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = IntroRepository::addIntro($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteIntro(array $data)
    {
        $response = IntroRepository::deleteIntro($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getIntroData(array $data)
    {
        $response = IntroRepository::getIntroData($data);
        return UtilsRepository::response($response);
    }

    public static function editIntro(array $data)
    {
        $rules = [
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = IntroRepository::editIntro($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}

?>

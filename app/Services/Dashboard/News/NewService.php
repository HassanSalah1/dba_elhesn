<?php
namespace App\Services\Dashboard\News;

use App\Repositories\Dashboard\News\NewRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class NewService
{


    public static function getNewsData(array $data)
    {
        return NewRepository::getNewsData($data);
    }

    public static function addNew(array $data)
    {
        $rules = [
            'title_ar' => 'required',
            'title_en' => 'required',
            'short_description_ar' => 'required',
            'short_description_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'category_id' => 'required',
            'image' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NewRepository::addNew($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }

    public static function deleteNew(array $data)
    {
        $response = NewRepository::deleteNew($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }

    public static function getNewData(array $data)
    {
        $response = NewRepository::getNewData($data);
        return UtilsRepository::response($response);
    }

    public static function editNew(array $data)
    {
        $rules = [
            'title_ar' => 'required',
            'title_en' => 'required',
            'short_description_ar' => 'required',
            'short_description_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'category_id' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NewRepository::editNew($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }


    public static function removeImage(array $data)
    {
        $response = NewRepository::removeImage($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }

}

?>

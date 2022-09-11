<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\GalleryRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class GalleryService
{


    public static function getGalleriesData(array $data)
    {
        return GalleryRepository::getGalleriesData($data);
    }

    public static function addGallery(array $data)
    {
        $rules = [
            'type' => 'required',
            'video_url' => 'required_without:image',
            'image' => 'required_without:video_url',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = GalleryRepository::addGallery($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteGallery(array $data)
    {
        $response = GalleryRepository::deleteGallery($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function restoreGallery(array $data)
    {
        $response = GalleryRepository::restoreGallery($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getGalleryData(array $data)
    {
        $response = GalleryRepository::getGalleryData($data);
        return UtilsRepository::response($response);
    }

    public static function editGallery(array $data)
    {
        $rules = [
            'title' => 'required',
            'name' => 'required',
            'position' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = GalleryRepository::editGallery($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}

?>

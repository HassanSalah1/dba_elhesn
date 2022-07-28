<?php

namespace App\Services\Dashboard\Notification;


use App\Repositories\Dashboard\Notification\NotificationRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class NotificationService
{

    public static function sendNotification(array $data)
    {
        $rules = [
            'title_ar' => 'required',
            'message_ar' => 'required',
            'title_en' => 'required',
            'message_en' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = NotificationRepository::sendNotification($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }
}

?>

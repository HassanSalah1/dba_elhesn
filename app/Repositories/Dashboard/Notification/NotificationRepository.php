<?php

namespace App\Repositories\Dashboard\Notification;

use App\Entities\NotificationType;
use App\Entities\Status;
use App\Entities\UserRoles;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use App\Models\User;

class NotificationRepository
{

    public static function sendNotification($data)
    {
        $users = User::where(function ($query) use ($data) {
            if (isset($data['user_id']) && count($data['user_id']) > 0) {
                $query->whereIn('id', $data['user_id']);
            }
            $query->where(['role' => UserRoles::CUSTOMER, 'status' => Status::ACTIVE]);
        })->get();
        if (count($users) > 0) {
            $notification_obj = [
                'title_ar' => $data['title_ar'],
                'message_ar' => $data['message_ar'],
                'title_en' => $data['title_en'],
                'message_en' => $data['message_en'],
                'type' => NotificationType::TEXT,
            ];
            foreach ($users as $user) {
                $notification_obj['user_id'] = $user->id;
                Notification::create($notification_obj);
            }
            $notification_obj['title'] = $data['title_ar'];
            $notification_obj['message'] = $data['message_ar'];
            $tokensAR = collect($users)->where([
                'lang' => 'ar',
                ['device_token', '!=', null]
            ])->pluck('device_token')->toArray();
            if (count($tokensAR) > 0)
                SendNotificationJob::dispatch($tokensAR, $notification_obj['title']
                    , $notification_obj['message'], $notification_obj);
            ////////////////////////////////////////////////////
            $notification_obj['title'] = $data['title_en'];
            $notification_obj['message'] = $data['message_en'];
            $tokensEN = collect($users)->where([
                'lang' => 'en',
                ['device_token', '!=', null]
            ])->pluck('device_token')->toArray();
            if (count($tokensEN) > 0)
                SendNotificationJob::dispatch($tokensEN, $notification_obj['title']
                    , $notification_obj['message'], $notification_obj);

        }
        return true;
    }

}

?>

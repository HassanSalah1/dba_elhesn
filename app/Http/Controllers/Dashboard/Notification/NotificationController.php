<?php

namespace App\Http\Controllers\Dashboard\Notification;

use App\Entities\Status;
use App\Entities\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class NotificationController extends Controller
{
    //
    public function showSendNotification()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.send_notification_title');
        $data['users'] = User::where(['role' => UserRoles::CUSTOMER, 'status' => Status::ACTIVE])
            ->get();
        return view('admin.notification.send_notification')->with($data);
    }

    public function sendNotification(Request $request)
    {
        $data = $request->all();
        return NotificationService::sendNotification($data);
    }

}

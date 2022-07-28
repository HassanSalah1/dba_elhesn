<?php

namespace App\Http\Controllers\Dashboard;

use App\Entities\BankTransferStatus;
use App\Entities\Key;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Dashboard\HomeRepository;
use App\Repositories\General\UtilsRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    // Dashboard home
    public function showHome()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $title = trans('admin.home_title');
        $usersCount = User::where(['role' => UserRoles::CUSTOMER])->count();
        $registerUsersCount = User::where(['role' => UserRoles::REGISTER])->count();
        $newOrders = Order::whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT])
            ->whereIn('status', [
                OrderStatus::WAIT,
                OrderStatus::ACCEPTED,
                OrderStatus::REFUSED,
                OrderStatus::EDITED,
                OrderStatus::PAYMENT_APPROVED,
                OrderStatus::PROGRESS
            ])->count();

        $progressOrders = Order::whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT])
            ->whereIn('status', [
                OrderStatus::RECEIVE_REFUSED,
                OrderStatus::SHIPPED
            ])->count();

        $completedOrders = Order::whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT])
            ->whereIn('status', [
                OrderStatus::COMPLETED,
            ])->count();

        $cancelledOrders = Order::whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT])
            ->whereIn('status', [
                OrderStatus::CANCELLED,
                OrderStatus::RECEIVE_REFUSED_APPROVED
            ])->count();

        $refusedOrders = Order::whereIn('type', [OrderType::DAMAIN, OrderType::DIRECT])
            ->whereIn('status', [
                OrderStatus::RECEIVE_REFUSED,
            ])->count();

        $bankTransfersCount = BankTransfer::with(['bank'])
            ->where(['status' => BankTransferStatus::WAIT])
            ->count();

        return view('admin.home', [
            'pageConfigs' => $pageConfigs,
            'title' => $title,
            'usersCount' => $usersCount,
            'registerUsersCount' => $registerUsersCount,
            'newOrders' => $newOrders,
            'progressOrders' => $progressOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'refusedOrders' => $refusedOrders,
            'bankTransfersCount' => $bankTransfersCount
        ]);
    }

    // upload images for editor
    public function uploadEditorImages(Request $request)
    {
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'file';
        $image_path = 'uploads/content/';
        $image = UtilsRepository::createImage($request, $image_name, $image_path, $file_id);
        if ($image !== false) {
            return response()->json([
                'location' => url($image)
            ]);
        }
    }

    // show about page
    public function showAbout()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['about_ar'] = Setting::where(['key' => Key::ABOUT_AR])->first();
        $data['about_en'] = Setting::where(['key' => Key::ABOUT_EN])->first();
        $data['title'] = trans('admin.about_title');
        return view('admin.settings.about')->with($data);
    }

    // save about POST request
    public function saveAbout(Request $request)
    {
        $data = $request->all();
        return HomeRepository::saveAbout($data);
    }

    // show terms page
    public function showTerms()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['terms_ar'] = Setting::where(['key' => Key::TERMS_AR])->first();
        $data['terms_en'] = Setting::where(['key' => Key::TERMS_EN])->first();
        $data['title'] = trans('admin.terms_title');
        return view('admin.settings.terms')->with($data);
    }

    // save terms POST request
    public function saveTerms(Request $request)
    {
        $data = $request->all();
        return HomeRepository::saveTerms($data);
    }

    // show privacy page
    public function showPrivacy()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['privacy_ar'] = Setting::where(['key' => Key::PRIVACY_AR])->first();
        $data['privacy_en'] = Setting::where(['key' => Key::PRIVACY_EN])->first();
        $data['title'] = trans('admin.privacy_title');
        return view('admin.settings.privacy')->with($data);
    }

    // save privacy POST request
    public function savePrivacy(Request $request)
    {
        $data = $request->all();
        return HomeRepository::savePrivacy($data);
    }

    // show setting page
    public function showSetting()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['email'] = Setting::where(['key' => Key::EMAIL])->first();
        $data['facebook'] = Setting::where(['key' => Key::FACEBOOK])->first();
        $data['twitter'] = Setting::where(['key' => Key::TWITTER])->first();
        $data['instagram'] = Setting::where(['key' => Key::INSTAGRAM])->first();
        $data['snapchat'] = Setting::where(['key' => Key::SNAPCHAT])->first();
        $data['app_percentage'] = Setting::where(['key' => Key::APP_PERCENTAGE])->first();
        $data['whatsapp'] = Setting::where(['key' => Key::WHATSAPP])->first();
        $data['telegram'] = Setting::where(['key' => Key::TELEGRAM])->first();
        $data['max_time_to_pay'] = Setting::where(['key' => Key::MAX_TIME_TO_PAY])->first();
        $data['max_time_to_approval_rejection'] = Setting::where(['key' => Key::MAX_TIME_TO_APPROVAL_REJECTION])->first();
        $data['max_time_to_choose_shipment'] = Setting::where(['key' => Key::MAX_TIME_TO_CHOOSE_SHIPMENT])->first();
        $data['title'] = trans('admin.settings_title');
        return view('admin.settings.setting')->with($data);
    }


    // save setting POST request
    public function saveSetting(Request $request)
    {
        $data = $request->all();
        return HomeRepository::saveSetting($data);
    }

    // show site home setting page
    public function showHomeSetting()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['small_about_ar'] = Setting::where(['key' => Key::SMALL_ABOUT_AR])->first();
        $data['small_about_en'] = Setting::where(['key' => Key::SMALL_ABOUT_EN])->first();

        $data['download_ar'] = Setting::where(['key' => Key::DOWNLOAD_AR])->first();
        $data['download_en'] = Setting::where(['key' => Key::DOWNLOAD_EN])->first();
        $data['direct_ar'] = Setting::where(['key' => Key::DIRECT_AR])->first();
        $data['direct_en'] = Setting::where(['key' => Key::DIRECT_EN])->first();
        $data['damain_ar'] = Setting::where(['key' => Key::DAMAIN_AR])->first();
        $data['damain_en'] = Setting::where(['key' => Key::DAMAIN_EN])->first();
        $data['bid_ar'] = Setting::where(['key' => Key::BID_AR])->first();
        $data['bid_en'] = Setting::where(['key' => Key::BID_EN])->first();
        $data['negotiation_ar'] = Setting::where(['key' => Key::NEGOTIATION_AR])->first();
        $data['negotiation_en'] = Setting::where(['key' => Key::NEGOTIATION_EN])->first();
        $data['google_play'] = Setting::where(['key' => Key::GOOGLE_PLAY])->first();
        $data['apple_store'] = Setting::where(['key' => Key::APPLE_STORE])->first();
        $data['title'] = trans('admin.site_home_title');
        return view('admin.settings.site-setting')->with($data);
    }

    // save setting POST request
    public function saveSiteSetting(Request $request)
    {
        $data = $request->all();
        return HomeRepository::saveSiteSetting($data);
    }
}

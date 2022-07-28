<?php

namespace App\Repositories\Api\Setting;

use App\Entities\HttpCode;
use App\Entities\Key;
use App\Http\Resources\CityResource;
use App\Http\Resources\ContactTypeResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\IntroResource;
use App\Http\Resources\UserGuideResource;
use App\Models\City;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\Country;
use App\Models\Faq;
use App\Models\Image;
use App\Models\Intro;
use App\Models\Setting;
use App\Models\UserGuide;
use App\Repositories\General\UtilsRepository;
use Illuminate\Support\Facades\App;

class SettingApiRepository
{

    // get About
    public static function getAbout(array $data)
    {
        $lang = App::getLocale();
        $setting = Setting::where(['key' => ($lang === 'en') ?
            Key::ABOUT_EN : Key::ABOUT_AR])->first();
        // return success response
        return [
            'data' => [
                'about' => $setting ? $setting->value : null
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getTerms(array $data)
    {

        $lang = App::getLocale();
        $setting = Setting::where(['key' => ($lang === 'en') ?
            Key::TERMS_EN : Key::TERMS_AR])->first();
        // return success response
        return [
            'data' => [
                'terms' => $setting ? $setting->value : null
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getPrivacy(array $data)
    {

        $lang = App::getLocale();
        $setting = Setting::where(['key' => ($lang === 'en') ?
            Key::PRIVACY_EN : Key::PRIVACY_AR])->first();
        // return success response
        return [
            'data' => [
                'privacy' => $setting ? $setting->value : null
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getFaqs(array $data)
    {
        $faqs = Faq::orderBy('id', 'DESC')->get();
        return [
            'data' => FaqResource::collection($faqs),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getUserGuides(array $data)
    {
        $guides = UserGuide::orderBy('id', 'DESC')->get();
        return [
            'data' => UserGuideResource::collection($guides),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function loadSocial()
    {
        $email = Setting::where(['key' => Key::EMAIL])->first();
        $facebook = Setting::where(['key' => Key::FACEBOOK])->first();
        $twitter = Setting::where(['key' => Key::TWITTER])->first();
        $instagram = Setting::where(['key' => Key::INSTAGRAM])->first();
        $snapchat = Setting::where(['key' => Key::SNAPCHAT])->first();
        $telegram = Setting::where(['key' => Key::TELEGRAM])->first();
        $whatsapp = Setting::where(['key' => Key::WHATSAPP])->first();
        return [
            'email' => $email ? $email->value : null,
            'facebook' => $facebook ? $facebook->value : null,
            'twitter' => $twitter ? $twitter->value : null,
            'instagram' => $instagram ? $instagram->value : null,
            'snapchat' => $snapchat ? $snapchat->value : null,
            'telegram' => $telegram ? $telegram->value : null,
            'whatsapp' => $whatsapp ? $whatsapp->value : null,
        ];
    }

    public static function getContactTypes(array $data)
    {
        $contactTypes = ContactType::withoutTrashed()
            ->orderBy('id', 'DESC')->get();

        return [
            'data' => [
                'contactTypes' => ContactTypeResource::collection($contactTypes),
                'social' => self::loadSocial()
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getIntros(array $data)
    {
        $intros = Intro::orderBy('id', 'DESC')->get();
        return [
            'data' => IntroResource::collection($intros),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getCountries(array $data)
    {
        $countries = Country::where(['status' => 1])->orderBy('id', 'DESC')->get();
        return [
            'data' => CountryResource::collection($countries),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getCities(array $data)
    {
        $cities = City::withoutTrashed()->where([
            'country_id' => $data['country_id'],
        ])->orderBy('id', 'DESC')->get();
        return [
            'data' => CityResource::collection($cities),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }


    /////////////////////////////
    public static function addContact(array $data)
    {
        $created = Contact::create([
            'user_id' => auth()->user()->id,
            'contact_type_id' => $data['contact_type_id'],
            'message' => $data['message'],
        ]);
        if ($created) {
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        } else {
            return [
                'message' => trans('api.general_error_message'),
                'code' => HttpCode::ERROR
            ];
        }
    }

    public static function uploadGeneralImage(array $data)
    {
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'image';
        $image_path = 'uploads/generalImages/';
        $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
        if ($image !== false) {
            $productImage = Image::create([
                'image' => $image,
            ]);
            return [
                'data' => ImageResource::make($productImage),
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function removeGeneralImage(array $data)
    {
        $productImage = Image::find($data['id']);
        if ($productImage) {
            $productImage->forceDelete();
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

}

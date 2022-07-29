<?php

namespace App\Repositories\Api\Setting;

use App\Entities\HttpCode;
use App\Entities\Key;
use App\Http\Resources\ActionResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\NewResource;
use App\Http\Resources\TeamResource;
use App\Models\Action;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Setting;
use App\Models\Team;
use Illuminate\Support\Facades\App;

class SettingApiRepository
{

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

    public static function getContactDetails(array $data)
    {

        $phone = Setting::where(['key' => Key::PHONE])->first();
        $latitude = Setting::where(['key' => Key::LATITUDE])->first();
        $longitude = Setting::where(['key' => Key::LONGITUDE])->first();

        $facebook = Setting::where(['key' => Key::FACEBOOK])->first();
        $twitter = Setting::where(['key' => Key::TWITTER])->first();
        $instagram = Setting::where(['key' => Key::INSTAGRAM])->first();
        $youtube = Setting::where(['key' => Key::YOUTUBE])->first();

        // return success response
        return [
            'data' => [
                'phone' => $phone ? $phone->value : null,
                'latitude' => $latitude ? (float)$latitude->value : null,
                'longitude' => $longitude ? (float)$longitude->value : null,
                'facebook' => $facebook ? $facebook->value : null,
                'twitter' => $twitter ? $twitter->value : null,
                'instagram' => $instagram ? $instagram->value : null,
                'youtube' => $youtube ? $youtube->value : null,
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function addContact(array $data)
    {
        $created = Contact::create([
            'user_id' => auth('api')->id(),
            'contact_type' => $data['contact_type'],
            'message' => $data['message'],
            'name' => isset($data['name']) ? $data['name'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
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


    public static function getTeams(array $data)
    {
        $lang = App::getLocale();
        $setting = Setting::where(['key' => ($lang === 'en') ?
            Key::TEAM_DESCRIPTION_EN : Key::TEAM_DESCRIPTION_AR])->first();
        $teams = Team::all();
        // return success response
        return [
            'data' => [
                'team_description' => $setting ? $setting->value : null,
                'teams' => TeamResource::collection($teams)
            ],
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }


    public static function getGallery(array $data)
    {
        $galleries = Gallery::where(function ($query) use ($data) {
            if (isset($data['type']) && $data['type'] === 'video') {
                $query->where('video_url', '!=', null);
            } else if (isset($data['type']) && $data['type'] === 'image') {
                $query->where('image', '!=', null);
            }
        })->orderBy('id', 'DESC')->paginate(10);
        $galleries->{'galleries'} = GalleryResource::collection($galleries);
        // return success response
        return [
            'data' => $galleries,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getNews(array $data)
    {
        $news = News::orderBy('id', 'DESC')->paginate(10);
        $news->{'news'} = NewResource::collection($news);
        // return success response
        return [
            'data' => $news,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getActions(array $data)
    {
        $actions = Action::orderBy('id', 'DESC')->paginate(10);
        $actions->{'actions'} = ActionResource::collection($actions);
        // return success response
        return [
            'data' => $actions,
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

}

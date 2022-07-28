<?php

namespace App\Repositories\Dashboard;


use App\Entities\Key;
use App\Models\Setting;
use App\Repositories\General\UtilsRepository;

class HomeRepository
{


    public static function saveAbout(array $data)
    {
        // about ar
        if (isset($data[Key::ABOUT_AR])) {
            $about_ar = Setting::where(['key' => Key::ABOUT_AR])->first();
            if ($about_ar) {
                $about_ar->update(['value' => $data[Key::ABOUT_AR]]);
            } else {
                Setting::create(['key' => Key::ABOUT_AR, 'value' => $data[Key::ABOUT_AR]]);
            }
        }

        // about en
        if (isset($data[Key::ABOUT_EN])) {
            $about_en = Setting::where(['key' => Key::ABOUT_EN])->first();
            if ($about_en) {
                $about_en->update(['value' => $data[Key::ABOUT_EN]]);
            } else {
                Setting::create(['key' => Key::ABOUT_EN, 'value' => $data[Key::ABOUT_EN]]);
            }
        }

        return UtilsRepository::response(true,
            trans('admin.process_success_message')
            , '');
    }


    public static function saveTerms(array $data)
    {
        // terms ar
        if (isset($data[Key::TERMS_AR])) {
            $terms_ar = Setting::where(['key' => Key::TERMS_AR])->first();
            if ($terms_ar) {
                $terms_ar->update(['value' => $data[Key::TERMS_AR]]);
            } else {
                Setting::create(['key' => Key::TERMS_AR, 'value' => $data[Key::TERMS_AR]]);
            }
        }

        // terms en
        if (isset($data[Key::TERMS_EN])) {
            $terms_en = Setting::where(['key' => Key::TERMS_EN])->first();
            if ($terms_en) {
                $terms_en->update(['value' => $data[Key::TERMS_EN]]);
            } else {
                Setting::create(['key' => Key::TERMS_EN, 'value' => $data[Key::TERMS_EN]]);
            }
        }

        return UtilsRepository::response(true, trans('admin.process_success_message')
            , '');
    }


    public static function savePrivacy(array $data)
    {
        // privacy ar
        if (isset($data[Key::PRIVACY_AR])) {
            $terms_ar = Setting::where(['key' => Key::PRIVACY_AR])->first();
            if ($terms_ar) {
                $terms_ar->update(['value' => $data[Key::PRIVACY_AR]]);
            } else {
                Setting::create(['key' => Key::PRIVACY_AR, 'value' => $data[Key::PRIVACY_AR]]);
            }
        }

        // privacy en
        if (isset($data[Key::PRIVACY_EN])) {
            $terms_en = Setting::where(['key' => Key::PRIVACY_EN])->first();
            if ($terms_en) {
                $terms_en->update(['value' => $data[Key::PRIVACY_EN]]);
            } else {
                Setting::create(['key' => Key::PRIVACY_EN, 'value' => $data[Key::PRIVACY_EN]]);
            }
        }

        return UtilsRepository::response(true, trans('admin.process_success_message')
            , '');
    }


    public static function saveHistory(array $data)

    {
        // HISTORY_AR
        if (isset($data[Key::HISTORY_AR])) {
            $terms_ar = Setting::where(['key' => Key::HISTORY_AR])->first();
            if ($terms_ar) {
                $terms_ar->update(['value' => $data[Key::HISTORY_AR]]);
            } else {
                Setting::create(['key' => Key::HISTORY_AR, 'value' => $data[Key::HISTORY_AR]]);
            }
        }

        // HISTORY_EN
        if (isset($data[Key::HISTORY_EN])) {
            $terms_en = Setting::where(['key' => Key::HISTORY_EN])->first();
            if ($terms_en) {
                $terms_en->update(['value' => $data[Key::HISTORY_EN]]);
            } else {
                Setting::create(['key' => Key::HISTORY_EN, 'value' => $data[Key::HISTORY_EN]]);
            }
        }

        return UtilsRepository::response(true, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }

    public static function saveSetting($data)
    {
        // facebook
        $facebook = Setting::where(['key' => Key::FACEBOOK])->first();
        if ($facebook) {
            $facebook->update([
                'value' => (isset($data[Key::FACEBOOK])) ? $data[Key::FACEBOOK] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::FACEBOOK,
                'value' => (isset($data[Key::FACEBOOK])) ? $data[Key::FACEBOOK] : null
            ]);
        }


        // twitter
        $twitter = Setting::where(['key' => Key::TWITTER])->first();
        if ($twitter) {
            $twitter->update([
                'value' => (isset($data[Key::TWITTER])) ? $data[Key::TWITTER] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::TWITTER,
                'value' => (isset($data[Key::TWITTER])) ? $data[Key::TWITTER] : null
            ]);
        }

        // telegram
        $telegram = Setting::where(['key' => Key::TELEGRAM])->first();
        if ($telegram) {
            $telegram->update([
                'value' => (isset($data[Key::TELEGRAM])) ? $data[Key::TELEGRAM] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::TELEGRAM,
                'value' => (isset($data[Key::TELEGRAM])) ? $data[Key::TELEGRAM] : null
            ]);
        }

        // whatsapp
        $whatsapp = Setting::where(['key' => Key::WHATSAPP])->first();
        if ($whatsapp) {
            $whatsapp->update([
                'value' => (isset($data[Key::WHATSAPP])) ? $data[Key::WHATSAPP] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::WHATSAPP,
                'value' => (isset($data[Key::WHATSAPP])) ? $data[Key::WHATSAPP] : null
            ]);
        }

        // instagram
        $instagram = Setting::where(['key' => Key::INSTAGRAM])->first();
        if ($instagram) {
            $instagram->update([
                'value' => (isset($data[Key::INSTAGRAM])) ? $data[Key::INSTAGRAM] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::INSTAGRAM,
                'value' => (isset($data[Key::INSTAGRAM])) ? $data[Key::INSTAGRAM] : null
            ]);
        }

        // snapchat
        $snapchat = Setting::where(['key' => Key::SNAPCHAT])->first();
        if ($snapchat) {
            $snapchat->update([
                'value' => (isset($data[Key::SNAPCHAT])) ? $data[Key::SNAPCHAT] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::SNAPCHAT,
                'value' => (isset($data[Key::SNAPCHAT])) ? $data[Key::SNAPCHAT] : null
            ]);
        }

        // email
        $email = Setting::where(['key' => Key::EMAIL])->first();
        if ($email) {
            $email->update([
                'value' => (isset($data[Key::EMAIL])) ? $data[Key::EMAIL] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::EMAIL,
                'value' => (isset($data[Key::EMAIL])) ? $data[Key::EMAIL] : null
            ]);
        }

        // app percentage
        $app_percentage = Setting::where(['key' => Key::APP_PERCENTAGE])->first();
        if ($app_percentage) {
            $app_percentage->update([
                'value' => (isset($data[Key::APP_PERCENTAGE])) ? $data[Key::APP_PERCENTAGE] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::APP_PERCENTAGE,
                'value' => (isset($data[Key::APP_PERCENTAGE])) ? $data[Key::APP_PERCENTAGE] : null
            ]);
        }

        // MAX_TIME_TO_PAY
        $max_time_to_pay = Setting::where(['key' => Key::MAX_TIME_TO_PAY])->first();
        if ($max_time_to_pay) {
            $max_time_to_pay->update([
                'value' => (isset($data[Key::MAX_TIME_TO_PAY])) ? $data[Key::MAX_TIME_TO_PAY] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::MAX_TIME_TO_PAY,
                'value' => (isset($data[Key::MAX_TIME_TO_PAY])) ? $data[Key::MAX_TIME_TO_PAY] : null
            ]);
        }

        // MAX_TIME_TO_APPROVAL_REJECTION
        $max_time_to_approval_rejection = Setting::where(['key' => Key::MAX_TIME_TO_APPROVAL_REJECTION])->first();
        if ($max_time_to_approval_rejection) {
            $max_time_to_approval_rejection->update([
                'value' => (isset($data[Key::MAX_TIME_TO_APPROVAL_REJECTION])) ? $data[Key::MAX_TIME_TO_APPROVAL_REJECTION] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::MAX_TIME_TO_APPROVAL_REJECTION,
                'value' => (isset($data[Key::MAX_TIME_TO_APPROVAL_REJECTION])) ? $data[Key::MAX_TIME_TO_APPROVAL_REJECTION] : null
            ]);
        }

        // max_time_to_choose_shipment
        $max_time_to_choose_shipment = Setting::where(['key' => Key::MAX_TIME_TO_CHOOSE_SHIPMENT])->first();
        if ($max_time_to_choose_shipment) {
            $max_time_to_choose_shipment->update([
                'value' => (isset($data[Key::MAX_TIME_TO_CHOOSE_SHIPMENT])) ? $data[Key::MAX_TIME_TO_CHOOSE_SHIPMENT] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::MAX_TIME_TO_CHOOSE_SHIPMENT,
                'value' => (isset($data[Key::MAX_TIME_TO_CHOOSE_SHIPMENT])) ? $data[Key::MAX_TIME_TO_CHOOSE_SHIPMENT] : null
            ]);
        }


        return UtilsRepository::response(true, trans('admin.process_success_message')
            , '');
    }

    public static function saveSiteSetting(array $data)
    {
        // small_about_ar
        $small_about_ar = Setting::where(['key' => Key::SMALL_ABOUT_AR])->first();
        if ($small_about_ar) {
            $small_about_ar->update([
                'value' => (isset($data[Key::SMALL_ABOUT_AR])) ? $data[Key::SMALL_ABOUT_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::SMALL_ABOUT_AR,
                'value' => (isset($data[Key::SMALL_ABOUT_AR])) ? $data[Key::SMALL_ABOUT_AR] : null
            ]);
        }

        // small_about_en
        $small_about_en = Setting::where(['key' => Key::SMALL_ABOUT_EN])->first();
        if ($small_about_en) {
            $small_about_en->update([
                'value' => (isset($data[Key::SMALL_ABOUT_EN])) ? $data[Key::SMALL_ABOUT_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::SMALL_ABOUT_EN,
                'value' => (isset($data[Key::SMALL_ABOUT_EN])) ? $data[Key::SMALL_ABOUT_EN] : null
            ]);
        }

        // direct_ar
        $direct_ar = Setting::where(['key' => Key::DIRECT_AR])->first();
        if ($direct_ar) {
            $direct_ar->update([
                'value' => (isset($data[Key::DIRECT_AR])) ? $data[Key::DIRECT_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DIRECT_AR,
                'value' => (isset($data[Key::DIRECT_AR])) ? $data[Key::DIRECT_AR] : null
            ]);
        }

        // direct_en
        $direct_en = Setting::where(['key' => Key::DIRECT_EN])->first();
        if ($direct_en) {
            $direct_en->update([
                'value' => (isset($data[Key::DIRECT_EN])) ? $data[Key::DIRECT_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DIRECT_EN,
                'value' => (isset($data[Key::DIRECT_EN])) ? $data[Key::DIRECT_EN] : null
            ]);
        }


        // bid_ar
        $bid_ar = Setting::where(['key' => Key::BID_AR])->first();
        if ($bid_ar) {
            $bid_ar->update([
                'value' => (isset($data[Key::BID_AR])) ? $data[Key::BID_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::BID_AR,
                'value' => (isset($data[Key::BID_AR])) ? $data[Key::BID_AR] : null
            ]);
        }

        // bid_en
        $bid_en = Setting::where(['key' => Key::BID_EN])->first();
        if ($bid_en) {
            $bid_en->update([
                'value' => (isset($data[Key::BID_EN])) ? $data[Key::BID_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DIRECT_EN,
                'value' => (isset($data[Key::BID_EN])) ? $data[Key::BID_EN] : null
            ]);
        }


        // download_ar
        $download_ar = Setting::where(['key' => Key::DOWNLOAD_AR])->first();
        if ($download_ar) {
            $download_ar->update([
                'value' => (isset($data[Key::DOWNLOAD_AR])) ? $data[Key::DOWNLOAD_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DOWNLOAD_AR,
                'value' => (isset($data[Key::DOWNLOAD_AR])) ? $data[Key::DOWNLOAD_AR] : null
            ]);
        }

        // download_en
        $download_en = Setting::where(['key' => Key::DOWNLOAD_EN])->first();
        if ($download_en) {
            $download_en->update([
                'value' => (isset($data[Key::DOWNLOAD_EN])) ? $data[Key::DOWNLOAD_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DOWNLOAD_EN,
                'value' => (isset($data[Key::DOWNLOAD_EN])) ? $data[Key::DOWNLOAD_EN] : null
            ]);
        }


        // negotiation_ar
        $negotiation_ar = Setting::where(['key' => Key::NEGOTIATION_AR])->first();
        if ($negotiation_ar) {
            $negotiation_ar->update([
                'value' => (isset($data[Key::NEGOTIATION_AR])) ? $data[Key::NEGOTIATION_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::NEGOTIATION_AR,
                'value' => (isset($data[Key::NEGOTIATION_AR])) ? $data[Key::NEGOTIATION_AR] : null
            ]);
        }

        // negotiation_en
        $negotiation_en = Setting::where(['key' => Key::NEGOTIATION_EN])->first();
        if ($negotiation_en) {
            $direct_en->update([
                'value' => (isset($data[Key::NEGOTIATION_EN])) ? $data[Key::NEGOTIATION_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::NEGOTIATION_EN,
                'value' => (isset($data[Key::NEGOTIATION_EN])) ? $data[Key::NEGOTIATION_EN] : null
            ]);
        }


        // damain_ar
        $damain_ar = Setting::where(['key' => Key::DAMAIN_AR])->first();
        if ($damain_ar) {
            $damain_ar->update([
                'value' => (isset($data[Key::DAMAIN_AR])) ? $data[Key::DAMAIN_AR] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DAMAIN_AR,
                'value' => (isset($data[Key::DAMAIN_AR])) ? $data[Key::DAMAIN_AR] : null
            ]);
        }

        // damain_en
        $damain_en = Setting::where(['key' => Key::DAMAIN_EN])->first();
        if ($damain_en) {
            $damain_en->update([
                'value' => (isset($data[Key::DAMAIN_EN])) ? $data[Key::DAMAIN_EN] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::DAMAIN_EN,
                'value' => (isset($data[Key::DAMAIN_EN])) ? $data[Key::DAMAIN_EN] : null
            ]);
        }

        // google_play
        $google_play = Setting::where(['key' => Key::GOOGLE_PLAY])->first();
        if ($google_play) {
            $google_play->update([
                'value' => (isset($data[Key::GOOGLE_PLAY])) ? $data[Key::GOOGLE_PLAY] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::GOOGLE_PLAY,
                'value' => (isset($data[Key::GOOGLE_PLAY])) ? $data[Key::GOOGLE_PLAY] : null
            ]);
        }

        // apple_store
        $apple_store = Setting::where(['key' => Key::APPLE_STORE])->first();
        if ($apple_store) {
            $apple_store->update([
                'value' => (isset($data[Key::APPLE_STORE])) ? $data[Key::APPLE_STORE] : null
            ]);
        } else {
            Setting::create([
                'key' => Key::APPLE_STORE,
                'value' => (isset($data[Key::APPLE_STORE])) ? $data[Key::APPLE_STORE] : null
            ]);
        }

        return UtilsRepository::response(true, trans('admin.process_success_message')
            , '');
    }


}

?>

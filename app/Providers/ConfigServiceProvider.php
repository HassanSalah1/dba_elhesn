<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        config([
            'laravellocalization.supportedLocales' => [
                'ar' => array('name' => 'Arabic', 'script' => 'Latn', 'native' => 'Arabic'),
                'en' => array('name' => 'English', 'script' => 'Latn', 'native' => 'English'),
//                'fr' => array('name' => 'French', 'script' => 'Latn', 'native' => 'French'),
//                'es' => array('name' => 'Spanish', 'script' => 'Latn', 'native' => 'Spanish'),
//                'tr' => array('name' => 'Turkish', 'script' => 'Latn', 'native' => 'Turkish'),
//                'ur' => array('name' => 'Urdu', 'script' => 'Latn', 'native' => 'Urdu'),
            ],
            'laravellocalization.useSessionLocale' => true,
            'laravellocalization.useAcceptLanguageHeader' => false,
            'laravellocalization.hideDefaultLocaleInURL' => true
        ]);
    }

}
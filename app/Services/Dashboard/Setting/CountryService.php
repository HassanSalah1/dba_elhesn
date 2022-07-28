<?php
namespace App\Services\Dashboard\Setting;

use App\Repositories\Dashboard\Setting\CountryRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class CountryService
{


    public static function getCountriesData(array $data)
    {
        return CountryRepository::getCountriesData($data);
    }


    public static function changeCountry(array $data)
    {
        $response = CountryRepository::changeCountry($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}
?>

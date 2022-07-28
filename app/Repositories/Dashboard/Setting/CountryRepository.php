<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;

class CountryRepository
{

    // get Countries and create datatable data.
    public static function getCountriesData(array $data)
    {
        $countries = Country::orderBy('id', 'DESC');
        return DataTables::of($countries)
            ->editColumn('flag', function ($country) {
                return '<a href="' . ($country->flag) . '" data-popup="lightbox">
                                <img src="' . ($country->flag) . '" class="img-rounded img-preview"
                                style="max-height:50px;max-width:50px;"></a>';
            })
            ->addColumn('actions', function ($country) {
                $ul = '<div class="form-check form-switch">
                          <input id="'. $country->id .'" onchange="changeCountryStatus(this);" class="form-check-input" type="checkbox" role="switch" '.( ($country->status === 1) ? 'checked' : '').'>
                        </div>';
                return $ul;
            })->make(true);
    }


    public static function changeCountry(array $data)
    {
        $country = Country::where(['id' => $data['id']])->first();
        if ($country) {
            $country->update(['status' => !$country->status]);
            return true;
        }
        return false;
    }

}

?>

<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\City;
use Yajra\DataTables\Facades\DataTables;

class CityRepository
{

    // get Cities and create datatable data.
    public static function getCitiesData(array $data)
    {
        $cities = City::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($cities)
            ->addColumn('country_name', function ($city) {
                return $city->country->name;
            })
            ->addColumn('actions', function ($city) {
                $ul = '';
                if ($city->deleted_at === null) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $city->id . '" onclick="deleteCity(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $city->id . '" onclick="restoreCity(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }


    public static function deleteCity(array $data)
    {
        $city = City::where(['id' => $data['id']])->first();
        if ($city) {
            $city->delete();
            return true;
        }
        return false;
    }

    public static function restoreCity(array $data)
    {
        $city = City::withTrashed()->where(['id' => $data['id']])->first();
        if ($city) {
            $city->restore();
            return true;
        }
        return false;
    }

}

?>

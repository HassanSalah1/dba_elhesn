<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\Intro;
use Yajra\DataTables\Facades\DataTables;

class IntroRepository
{

    // get Intros and create datatable data.
    public static function getIntrosData(array $data)
    {
        $intros = Intro::orderBy('id' , 'DESC')->get();
        return DataTables::of($intros)
            ->addColumn('actions', function ($intro) {
                $ul = '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $intro->id . '" onclick="editIntro(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $intro->id . '" onclick="deleteIntro(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addIntro(array $data)
    {
        $intros = Intro::orderBy('id' , 'DESC')->count();
        if($intros < 3){
            $introData = [
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'],
                'description_ar' => $data['description_ar'],
                'description_en' => $data['description_en'],
            ];

            $created = Intro::create($introData);
            if ($created) {
                return true;
            }
        }
        return false;
    }

    public static function deleteIntro(array $data)
    {
        $intro = Intro::where(['id' => $data['id']])->first();
        if ($intro) {
            $intro->forceDelete();
            return true;
        }
        return false;
    }

    public static function getIntroData(array $data)
    {
        $intro = Intro::where(['id' => $data['id']])->first();
        if ($intro) {
            return $intro;
        }
        return false;
    }

    public static function editIntro(array $data)
    {
        $intro = Intro::where(['id' => $data['id']])->first();
        if ($intro) {
            $introData = [
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'],
                'description_ar' => $data['description_ar'],
                'description_en' => $data['description_en'],
            ];
            $updated = $intro->update($introData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

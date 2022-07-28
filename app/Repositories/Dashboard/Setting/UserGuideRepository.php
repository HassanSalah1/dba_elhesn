<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\UserGuide;
use App\Repositories\General\UtilsRepository;
use Yajra\DataTables\Facades\DataTables;

class UserGuideRepository
{

    // get UserGuides and create datatable data.
    public static function getUserGuidesData(array $data)
    {
        $userGuides = UserGuide::orderBy('id' , 'DESC');
        return DataTables::of($userGuides)
            ->editColumn('image', function ($userGuide) {
                if ($userGuide->image !== null && file_exists($userGuide->image)) {
                    return '<a href="' . url($userGuide->image) . '" data-popup="lightbox">
                                <img src="' . url($userGuide->image) . '" class="img-rounded img-preview"
                                style="max-height:50px;max-width:50px;"></a>';
                }
            })
            ->addColumn('actions', function ($userGuide) {
                $ul = '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $userGuide->id . '" onclick="editUserGuide(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $userGuide->id . '" onclick="deleteUserGuide(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addUserGuide(array $data)
    {
        $userGuideData = [
            'description_ar' => $data['description_ar'],
            'description_en' => $data['description_en'],
        ];
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'image';
        $image_path = 'uploads/user_guides/';
        $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
        if ($image !== false) {
            $userGuideData['image'] = $image;
            $created = UserGuide::create($userGuideData);
            if ($created) {
                return true;
            }
        }
        return false;
    }

    public static function deleteUserGuide(array $data)
    {
        $userGuide = UserGuide::where(['id' => $data['id']])->first();
        if ($userGuide) {
            if (file_exists($userGuide->image)) {
                unlink($userGuide->image);
            }
            $userGuide->forceDelete();
            return true;
        }
        return false;
    }

    public static function getUserGuideData(array $data)
    {
        $userGuide = UserGuide::where(['id' => $data['id']])->first();
        if ($userGuide) {
            $userGuide->image = $userGuide->image ? url($userGuide->image) : null;
            return $userGuide;
        }
        return false;
    }

    public static function editUserGuide(array $data)
    {
        $userGuide = UserGuide::where(['id' => $data['id']])->first();
        if ($userGuide) {
            $userGuideData = [
                'description_ar' => $data['description_ar'],
                'description_en' => $data['description_en'],
            ];
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/user_guides/';
            $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($image !== false) {
                $userGuideData['image'] = $image;
                if ($userGuide->image && file_exists($userGuide->image)) {
                    unlink($userGuide->image);
                }
            }
            $updated = $userGuide->update($userGuideData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\Committee;
use App\Repositories\General\UtilsRepository;
use Yajra\DataTables\Facades\DataTables;

class CommitteeRepository
{

    // get Committees and create datatable data.
    public static function getCommitteesData(array $data)
    {
        $teams = Committee::orderBy('id', 'DESC');
        return DataTables::of($teams)
            ->addColumn('actions', function ($team) {
                $ul = '';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $team->id . '" onclick="editCommittee(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $team->id . '" onclick="deleteCommittee(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addCommittee(array $data)
    {
        $teamData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'description_ar' => $data['description_ar'],
            'description_en' => $data['description_en'],
        ];
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'image';
        $image_path = 'uploads/committees/';
        $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
        if ($image !== false) {
            $teamData['image'] = $image;
        }
        $created = Committee::create($teamData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteCommittee(array $data)
    {
        $team = Committee::where(['id' => $data['id']])->first();
        if ($team) {
            $team->delete();
            return true;
        }
        return false;
    }

    public static function restoreCommittee(array $data)
    {
        $bank = Committee::where(['id' => $data['id']])->first();
        if ($bank) {
            $bank->restore();
            return true;
        }
        return false;
    }

    public static function getCommitteeData(array $data)
    {
        $team = Committee::where(['id' => $data['id']])->first();
        if ($team) {
            $team->image = $team->image ? url($team->image) : null;
            return $team;
        }
        return false;
    }

    public static function editCommittee(array $data)
    {
        $team = Committee::where(['id' => $data['id']])->first();
        if ($team) {
            $teamData = [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'description_ar' => $data['description_ar'],
                'description_en' => $data['description_en'],
            ];
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/committees/';
            $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($image !== false) {
                $teamData['image'] = $image;
                if ($team->image && file_exists($team->image)) {
                    unlink($team->image);
                }
            }
            $updated = $team->update($teamData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

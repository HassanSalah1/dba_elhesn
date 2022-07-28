<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\ContactType;
use Yajra\DataTables\Facades\DataTables;

class ContactTypeRepository
{

    // get ContactTypes and create datatable data.
    public static function getContactTypesData(array $data)
    {
        $contactTypes = ContactType::withTrashed()
            ->orderBy('id' , 'DESC');
        return DataTables::of($contactTypes)
            ->addColumn('actions', function ($contactType) {
                $ul = '';
                if ($contactType->deleted_at === null) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $contactType->id . '" onclick="editContactType(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $contactType->id . '" onclick="deleteContactType(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $contactType->id . '" onclick="restoreContactType(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function addContactType(array $data)
    {
        $contactTypeData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
        ];

        $created = ContactType::create($contactTypeData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteContactType(array $data)
    {
        $contactType = ContactType::where(['id' => $data['id']])->first();
        if ($contactType) {
            $contactType->delete();
            return true;
        }
        return false;
    }

    public static function restoreContactType(array $data)
    {
        $contactType = ContactType::withTrashed()->where(['id' => $data['id']])->first();
        if ($contactType) {
            $contactType->restore();
            return true;
        }
        return false;
    }

    public static function getContactTypeData(array $data)
    {
        $contactType = ContactType::where(['id' => $data['id']])->first();
        if ($contactType) {
            return $contactType;
        }
        return false;
    }

    public static function editContactType(array $data)
    {
        $contactType = ContactType::where(['id' => $data['id']])->first();
        if ($contactType) {
            $contactTypeData = [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
            ];
            $updated = $contactType->update($contactTypeData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

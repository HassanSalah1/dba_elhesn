<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\NegotiationPercentage;
use Yajra\DataTables\Facades\DataTables;

class NegotiationPercentRepository
{

    // get NegotiationPercentages and create datatable data.
    public static function getNegotiationPercentsData(array $data)
    {
        $negotiationPercentages = NegotiationPercentage::orderBy('id', 'DESC')->get();
        return DataTables::of($negotiationPercentages)
            ->addColumn('actions', function ($negotiationPercentage) {
                $ul = '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $negotiationPercentage->id . '" onclick="editNegotiationPercentage(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $negotiationPercentage->id . '" onclick="deleteNegotiationPercentage(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addNegotiationPercent(array $data)
    {
        $negotiationPercentageData = [
            'percent' => $data['percent'],
        ];

        $created = NegotiationPercentage::create($negotiationPercentageData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteNegotiationPercent(array $data)
    {
        $negotiationPercentage = NegotiationPercentage::where(['id' => $data['id']])->first();
        if ($negotiationPercentage) {
            $negotiationPercentage->forceDelete();
            return true;
        }
        return false;
    }

    public static function getNegotiationPercentData(array $data)
    {
        $negotiationPercentage = NegotiationPercentage::where(['id' => $data['id']])->first();
        if ($negotiationPercentage) {
            return $negotiationPercentage;
        }
        return false;
    }

    public static function editNegotiationPercent(array $data)
    {
        $negotiationPercentage = NegotiationPercentage::where(['id' => $data['id']])->first();
        if ($negotiationPercentage) {
            $negotiationPercentageData = [
                'percent' => $data['percent'],
            ];
            $updated = $negotiationPercentage->update($negotiationPercentageData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\NegotiationPeriod;
use Yajra\DataTables\Facades\DataTables;

class NegotiationPeriodRepository
{

    // get NegotiationPeriods and create datatable data.
    public static function getNegotiationPeriodsData(array $data)
    {
        $negotiationPeriods = NegotiationPeriod::orderBy('id', 'DESC')->get();
        return DataTables::of($negotiationPeriods)
             ->editColumn('type', function ($negotiationPeriod) {
                 return trans('admin.'.$negotiationPeriod->type);
             })
            ->addColumn('actions', function ($negotiationPeriod) {
                $ul = '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $negotiationPeriod->id . '" onclick="editNegotiationPeriod(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $negotiationPeriod->id . '" onclick="deleteNegotiationPeriod(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addNegotiationPeriod(array $data)
    {
        $negotiationPeriodData = [
            'period' => $data['period'],
            'type' => $data['type'],
        ];

        $created = NegotiationPeriod::create($negotiationPeriodData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteNegotiationPeriod(array $data)
    {
        $negotiationPeriod = NegotiationPeriod::where(['id' => $data['id']])->first();
        if ($negotiationPeriod) {
            $negotiationPeriod->forceDelete();
            return true;
        }
        return false;
    }

    public static function getNegotiationPeriodData(array $data)
    {
        $negotiationPeriod = NegotiationPeriod::where(['id' => $data['id']])->first();
        if ($negotiationPeriod) {
            return $negotiationPeriod;
        }
        return false;
    }

    public static function editNegotiationPeriod(array $data)
    {
        $negotiationPeriod = NegotiationPeriod::where(['id' => $data['id']])->first();
        if ($negotiationPeriod) {
            $negotiationPeriodData = [
                'period' => $data['period'],
                'type' => $data['type'],
            ];
            $updated = $negotiationPeriod->update($negotiationPeriodData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

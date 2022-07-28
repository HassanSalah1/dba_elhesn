<?php
namespace App\Repositories\Dashboard\Order\Setting;

use App\Models\PaymentMethod;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodRepository
{

    // get PaymentMethods and create datatable data.
    public static function getPaymentMethodsData(array $data)
    {
        $paymentMethods = PaymentMethod::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($paymentMethods)
            ->addColumn('actions', function ($paymentMethod) {
                $ul = '';
                if ($paymentMethod->deleted_at === null) {
//                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $paymentMethod->id . '" onclick="editPaymentMethod(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
//                   ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $paymentMethod->id . '" onclick="deletePaymentMethod(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $paymentMethod->id . '" onclick="restorePaymentMethod(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }


    public static function deletePaymentMethod(array $data)
    {
        $paymentMethod = PaymentMethod::where(['id' => $data['id']])->first();
        if ($paymentMethod) {
            $paymentMethod->delete();
            return true;
        }
        return false;
    }

    public static function restorePaymentMethod(array $data)
    {
        $paymentMethod = PaymentMethod::withTrashed()->where(['id' => $data['id']])->first();
        if ($paymentMethod) {
            $paymentMethod->restore();
            return true;
        }
        return false;
    }
}

?>

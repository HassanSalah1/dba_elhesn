<?php
namespace App\Repositories\Dashboard\Order;

use App\Entities\BankTransferStatus;
use App\Entities\CreditType;
use App\Entities\NotificationType;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Jobs\SendNotificationFCMJob;
use App\Models\BankTransfer;
use App\Models\Credit;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankTransferRepository
{

    // get BankTransfers and create datatable data.
    public static function getBankTransfersData(array $data)
    {
        $bankTransfers = BankTransfer::with(['bank'])
            ->where(['status' => $data['status']])
            ->orderBy('id', 'DESC');

        return DataTables::of($bankTransfers)
            ->addColumn('user', function ($bankTransfer) {
                $ul = '<ul>';
                if ($bankTransfer->order_id != null) {
                    $ul .= '<li>' . trans('admin.pay_order') . '</li>';
                } else {
                    $ul .= '<li>' . trans('admin.charge_credit') . '</li>';
                    $ul .= '<li>' . @$bankTransfer->user->name . '</li>';
                    $ul .= '<li>' . @$bankTransfer->user->full_phone . '</li>';
                }
                $ul .= '</ul>';
                return $ul;
            })
            ->editColumn('image', function ($bankTransfer) {
                if ($bankTransfer->image !== null && file_exists($bankTransfer->image)) {
                    return '<a href="' . url($bankTransfer->image) . '" data-popup="lightbox">
                                <img src="' . url($bankTransfer->image) . '" class="img-rounded img-preview"
                                style="max-height:50px;max-width:50px;"></a>';
                }
            })
            ->addColumn('price', function ($bankTransfer) {
                $order = $bankTransfer->order;
                if ($order && $order->type === OrderType::DAMAIN) {
                    $pricing = $order->damainOrder->calc_pricing();
                    return $pricing['total'];
                } else {
                    return $bankTransfer->amount;
                }
            })
            ->addColumn('actions', function ($bankTransfer) {
                $ul = '';
                if ($bankTransfer->status === BankTransferStatus::WAIT) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.approve_action') . '" id="' . $bankTransfer->id . '" onclick="approveBankTransfer(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="check"></i></a> ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.refuse_action') . '" id="' . $bankTransfer->id . '" onclick="refuseBankTransfer(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function changeBankTransferStatus(array $data)
    {
        $bankTransfer = BankTransfer::where([
            'id' => $data['id'],
            'status' => BankTransferStatus::WAIT
        ])->first();
        if ($bankTransfer) {
            DB::beginTransaction();
            try {
                $bankTransfer->update([
                    'status' => $data['status']
                ]);
                $extraData = [];
                if ($bankTransfer->order) {
                    $bankTransfer->order->update([
                        'status' => ($data['status'] === BankTransferStatus::APPROVED) ?
                            OrderStatus::PROGRESS : OrderStatus::ACCEPTED
                    ]);

                    if (($data['status'] === BankTransferStatus::APPROVED)) {
                        $user = ($bankTransfer->order->user_type === OrderUserType::SELLER)
                            ? $bankTransfer->order->user : $bankTransfer->order->other_user;
                        $notification_obj = [
                            'title_key' => 'notification_payed_order_title',
                            'message_key' => 'notification_payed_order_message',
                            'user_id' => $user->id,
                            'order_id' => $bankTransfer->order->id,
                            'type' => NotificationType::ORDER
                        ];


                        if ($bankTransfer->order->type === OrderType::DAMAIN) {
                            $extraData['product_name'] = $bankTransfer->order->damainOrder->name;
                        } else {
                            $extraData['product_name'] = $bankTransfer->order->normalOrder->product->name;
                        }
                        SendNotificationFCMJob::dispatch($user, $notification_obj, $extraData);
                    }
                }else if ($bankTransfer->user_id !== null) {
                    if ($data['status'] === BankTransferStatus::APPROVED) {
                        Credit::create([
                            'user_id' => $bankTransfer->user_id,
                            'amount' => $bankTransfer->amount,
                            'type' => CreditType::CHARGE,
                            'payment_method_id' => $bankTransfer->payment_method_id,
                            'bank_transfer_id' => $bankTransfer->id
                        ]);
                    }

                    // send notification to buyer
                    $user = $bankTransfer->user;
                    $notification_obj = [
                        'title_key' => ($data['status'] === BankTransferStatus::APPROVED) ?
                            'notification_approved_bank_transfer_title' : 'notification_refused_bank_transfer_title',
                        'message_key' => ($data['status'] === BankTransferStatus::APPROVED) ?
                            'notification_approved_bank_transfer_message' :  'notification_refused_bank_transfer_message',
                        'user_id' => $user->id,
                        'type' => NotificationType::CREDIT
                    ];
                    SendNotificationFCMJob::dispatch($user, $notification_obj , $extraData);
                }

                DB::commit();
                return true;
            } catch (\Exception $ex) {
                DB::rollBack();
            }
        }
        return false;
    }


}

?>

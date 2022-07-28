<?php
namespace App\Repositories\Dashboard\Withdraw;

use App\Entities\BankTransferStatus;
use App\Entities\CreditType;
use App\Entities\NotificationType;
use App\Entities\WithdrawRequestStatus;
use App\Jobs\SendNotificationFCMJob;
use App\Models\Credit;
use App\Models\WithdrawRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WithdrawRequestRepository
{

    // get WithdrawRequests and create datatable data.
    public static function getWithdrawRequestsData(array $data)
    {
        $withdrawRequests = WithdrawRequest::where(['status' => $data['status']])
            ->orderBy('id', 'DESC');

        return DataTables::of($withdrawRequests)
            ->addColumn('user', function ($withdrawRequest) {
                $ul = '<ul>';
                $ul .= '<li>' . @$withdrawRequest->user->name . '</li>';
                $ul .= '<li>' . @$withdrawRequest->user->full_phone . '</li>';
                $ul .= '</ul>';
                return $ul;
            })
            ->addColumn('actions', function ($withdrawRequest) {
                $ul = '';
                if ($withdrawRequest->status === WithdrawRequestStatus::WAIT) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.approve_action') . '" id="' . $withdrawRequest->id . '" onclick="approveWithdrawRequest(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="check"></i></a> ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.refuse_action') . '" id="' . $withdrawRequest->id . '" onclick="refuseWithdrawRequest(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function changeWithdrawRequestStatus(array $data)
    {
        $withdrawRequest = WithdrawRequest::where([
            'id' => $data['id'],
            'status' => WithdrawRequestStatus::WAIT
        ])->first();
        if ($withdrawRequest) {
            DB::beginTransaction();
            try {
                $withdrawRequest->update([
                    'status' => $data['status']
                ]);

                if ($data['status'] === WithdrawRequestStatus::APPROVED) {
                    Credit::create([
                        'user_id' => $withdrawRequest->user_id,
                        'amount' => $withdrawRequest->amount,
                        'type' => CreditType::WITHDRAW,
                    ]);
                }
                DB::commit();
                $user = $withdrawRequest->user;
                $notification_obj = [
                    'title_key' => ($data['status'] === BankTransferStatus::APPROVED) ?
                        'notification_approved_transfer_title' : 'notification_refused_transfer_title',
                    'message_key' => ($data['status'] === BankTransferStatus::APPROVED) ?
                        'notification_approved_transfer_message' :  'notification_refused_transfer_message',
                    'user_id' => $user->id,
                    'type' => NotificationType::CREDIT
                ];
                SendNotificationFCMJob::dispatch($user, $notification_obj , []);
                return true;
            } catch (\Exception $ex) {
                DB::rollBack();
            }
        }
        return false;
    }


}

?>

<?php

namespace App\Http\Controllers\Dashboard\Withdraw;

use App\Entities\WithdrawRequestStatus;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\Withdraw\WithdrawRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class WithdrawRequestController extends Controller
{
    //
    public function showNewWithdrawRequests()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.new_withdraw_requests_title');

        $data['debatable_names'] = array('#', trans('admin.user'),
            trans('admin.amount'), trans('admin.actions'));
        $data['status'] = WithdrawRequestStatus::WAIT;
        return view('admin.withdraw.withdraw_request')->with($data);
    }

    public function showApprovedWithdrawRequests()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.approved_withdraw_requests_title');

        $data['debatable_names'] = array('#', trans('admin.user'),
            trans('admin.amount'));
        $data['status'] = WithdrawRequestStatus::APPROVED;
        return view('admin.withdraw.withdraw_request')->with($data);
    }

    public function showRefusedWithdrawRequests()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.refused_withdraw_requests_title');
        $data['debatable_names'] = array('#', trans('admin.user'),
            trans('admin.amount'));
        $data['status'] = WithdrawRequestStatus::REFUSED;
        return view('admin.withdraw.withdraw_request')->with($data);
    }

    public function getWithdrawRequestsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return WithdrawRequestService::getWithdrawRequestsData($data);
    }

    public function changeWithdrawRequestStatus(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return WithdrawRequestService::changeWithdrawRequestStatus($data);
    }


}

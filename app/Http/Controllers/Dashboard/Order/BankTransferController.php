<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Entities\BankTransferStatus;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\Order\BankTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BankTransferController extends Controller
{
    //
    public function showNewBankTransfers()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.new_bank_transfers_title');

        $data['debatable_names'] = array(trans('admin.order_id'), trans('admin.user'),
            trans('admin.bank_name'), trans('admin.account_number'), trans('admin.price'),
            trans('admin.image'), trans('admin.actions'));
        $data['status'] = BankTransferStatus::WAIT;
        return view('admin.order.bank_transfer')->with($data);
    }

    public function showApprovedBankTransfers()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.approved_bank_transfers_title');

        $data['debatable_names'] = array(trans('admin.order_id'), trans('admin.user'),
            trans('admin.bank_name'), trans('admin.account_number'), trans('admin.price'),
            trans('admin.image'));
        $data['status'] = BankTransferStatus::APPROVED;
        return view('admin.order.bank_transfer')->with($data);
    }

    public function showRefusedBankTransfers()
    {
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.refused_bank_transfers_title');
        $data['debatable_names'] = array(trans('admin.order_id'), trans('admin.user'),
            trans('admin.bank_name'), trans('admin.account_number'), trans('admin.price'),
            trans('admin.image'));
        $data['status'] = BankTransferStatus::REFUSED;
        return view('admin.order.bank_transfer')->with($data);
    }

    public function getBankTransfersData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return BankTransferService::getBankTransfersData($data);
    }

    public function changeBankTransferStatus(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return BankTransferService::changeBankTransferStatus($data);
    }


}

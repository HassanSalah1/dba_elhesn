<?php

namespace App\Http\Controllers\Dashboard\Order\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Order\Setting\BankAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BankAccountController extends Controller
{
    //
    public function showBankAccounts()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.bank_accounts_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.account_number'), trans('admin.actions'));
        return view('admin.order.settings.bank_accounts')->with($data);
    }

    public function getBankAccountsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return BankAccountService::getBankAccountsData($data);
    }

    public function addBankAccount(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return BankAccountService::addBankAccount($data);
    }

    public function deleteBankAccount(Request $request)
    {
        $data = $request->all();
        return BankAccountService::deleteBankAccount($data);
    }

    public function restoreBankAccount(Request $request)
    {
        $data = $request->all();
        return BankAccountService::restoreBankAccount($data);
    }


    public function getBankAccountData(Request $request)
    {
        $data = $request->all();
        return BankAccountService::getBankAccountData($data);
    }

    public function editBankAccount(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return BankAccountService::editBankAccount($data);
    }

}

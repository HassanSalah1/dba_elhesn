<?php
namespace App\Services\Dashboard\Order\Setting;

use App\Repositories\Dashboard\Order\Setting\BankAccountRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class BankAccountService
{

    public static function getBankAccountsData(array $data)
    {
        return BankAccountRepository::getBankAccountsData($data);
    }

    public static function addBankAccount(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'account_number' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = BankAccountRepository::addBankAccount($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function deleteBankAccount(array $data)
    {
        $response = BankAccountRepository::deleteBankAccount($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function getBankAccountData(array $data)
    {
        $response = BankAccountRepository::getBankAccountData($data);
        return UtilsRepository::response($response);
    }

    public static function restoreBankAccount(array $data)
    {
        $response = BankAccountRepository::restoreBankAccount($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

    public static function editBankAccount(array $data)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'account_number' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = BankAccountRepository::editBankAccount($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

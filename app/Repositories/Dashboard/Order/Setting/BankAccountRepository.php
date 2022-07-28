<?php
namespace App\Repositories\Dashboard\Order\Setting;

use App\Models\BankAccount;
use Yajra\DataTables\Facades\DataTables;

class BankAccountRepository
{

    // get BankAccounts and create datatable data.
    public static function getBankAccountsData(array $data)
    {
        $bankAccounts = BankAccount::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($bankAccounts)
            ->addColumn('actions', function ($bankAccount) {
                $ul = '';
                if ($bankAccount->deleted_at === null) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $bankAccount->id . '" onclick="editBankAccount(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $bankAccount->id . '" onclick="deleteBankAccount(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $bankAccount->id . '" onclick="restoreBankAccount(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function addBankAccount(array $data)
    {
        $bankAccountData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'account_number' => $data['account_number'],
        ];
        $created = BankAccount::create($bankAccountData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteBankAccount(array $data)
    {
        $bankAccount = BankAccount::where(['id' => $data['id']])->first();
        if ($bankAccount) {
            $bankAccount->delete();
            return true;
        }
        return false;
    }

    public static function restoreBankAccount(array $data)
    {
        $bankAccount = BankAccount::withTrashed()->where(['id' => $data['id']])->first();
        if ($bankAccount) {
            $bankAccount->restore();
            return true;
        }
        return false;
    }

    public static function getBankAccountData(array $data)
    {
        $bankAccount = BankAccount::where(['id' => $data['id']])->first();
        if ($bankAccount) {
            return $bankAccount;
        }
        return false;
    }

    public static function editBankAccount(array $data)
    {
        $bankAccount = BankAccount::where(['id' => $data['id']])->first();
        if ($bankAccount) {
            $bankAccountData = [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'account_number' => $data['account_number'],
            ];
            $updated = $bankAccount->update($bankAccountData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>

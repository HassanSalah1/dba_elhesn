<?php
namespace App\Services\Dashboard\Withdraw;

use App\Repositories\Dashboard\Withdraw\WithdrawRequestRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class WithdrawRequestService
{

    public static function getWithdrawRequestsData(array $data)
    {
        return WithdrawRequestRepository::getWithdrawRequestsData($data);
    }

    public static function changeWithdrawRequestStatus(array $data)
    {
        $rules = [
            'status' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = WithdrawRequestRepository::changeWithdrawRequestStatus($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

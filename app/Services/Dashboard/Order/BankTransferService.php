<?php
namespace App\Services\Dashboard\Order;

use App\Repositories\Dashboard\Order\BankTransferRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class BankTransferService
{

    public static function getBankTransfersData(array $data)
    {
        return BankTransferRepository::getBankTransfersData($data);
    }

    public static function changeBankTransferStatus(array $data)
    {
        $rules = [
            'status' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = BankTransferRepository::changeBankTransferStatus($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>

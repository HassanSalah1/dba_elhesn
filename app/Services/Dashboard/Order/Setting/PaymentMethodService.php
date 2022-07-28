<?php
namespace App\Services\Dashboard\Order\Setting;

use App\Repositories\Dashboard\Order\Setting\PaymentMethodRepository;
use App\Repositories\General\UtilsRepository;

class PaymentMethodService
{

    public static function getPaymentMethodsData(array $data)
    {
        return PaymentMethodRepository::getPaymentMethodsData($data);
    }


    public static function deletePaymentMethod(array $data)
    {
        $response = PaymentMethodRepository::deletePaymentMethod($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }


    public static function restorePaymentMethod(array $data)
    {
        $response = PaymentMethodRepository::restorePaymentMethod($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }

}

?>
